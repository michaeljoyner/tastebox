<?php

namespace App\Purchases;

use App\DatePresenter;
use App\Mail\AwaitingPaymentConfirmation;
use App\Meals\Meal;
use App\Orders\Menu;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Order extends Model
{

    const STATUS_CREATED = 'created';
    const STATUS_PENDING = 'pending';
    const STATUS_OPEN = 'open';
    const STATUS_COMPLETE = 'complete';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'order_key',
        'price_in_cents',
        'status',
        'is_paid',
        'user_id'
    ];

    protected $appends = [
        'order_date'
    ];

    protected $casts = ['is_paid' => 'boolean', 'confirmation_sent' => 'boolean'];

    public function customerFullname(): string
    {
        return trim(sprintf("%s %s", $this->first_name, $this->last_name));
    }

    public function scopeUnconfirmed(Builder $query)
    {
        return $query->where('confirmation_sent', false);
    }

    public function scopePending(Builder $query)
    {
        return $query->where('status', static::STATUS_PENDING);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('delivery_date', '>=', now()->startOfDay())
            ->where('is_paid', true)
            ->where('status', self::STATUS_OPEN);
    }

    public static function hasCurrentPending(): bool
    {
        return !!static::pending()->where('created_at', '>=', now()->subDays(7))->count();
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public static function makeNew(array $customer, Collection $kits, Discount $discount): Order
    {
        $order = static::create([
            'user_id'        => $customer['user_id'] ?? null,
            'first_name'     => $customer['first_name'],
            'last_name'      => $customer['last_name'],
            'email'          => $customer['email'] ?? '',
            'phone'          => $customer['phone'] ?? 'not given',
            'order_key'      => Str::uuid()->toString(),
            'price_in_cents' => static::checkOrderPrice($kits) * 100,
            'status'         => self::STATUS_CREATED,
        ]);

        $order->applyDiscount($discount);

        $kits->each(fn($kit) => $order->addKit($kit));

        return $order;

    }

    public static function manual(array $customer, Kit $kit): self
    {
        $order = static::create([
            'first_name'     => $customer['first_name'],
            'last_name'      => $customer['last_name'],
            'email'          => $customer['email'] ?? '',
            'phone'          => $customer['phone'] ?? 'not given',
            'order_key'      => Str::uuid()->toString(),
            'price_in_cents' => $kit->price() * 100,
            'status'         => self::STATUS_OPEN,
            'is_paid'        => true,
        ]);

        $order->addKit($kit);

        return $order;
    }

    private static function checkOrderPrice(Collection $kits)
    {
        return $kits
            ->filter(fn(Kit $kit) => $kit->eligibleForOrder())
            ->sum(fn(Kit $kit) => $kit->price());
    }

    private function applyDiscount(Discount $discount)
    {
        if (!$discount->isValid()) {
            return;
        }

        $this->price_in_cents = $discount->discount($this->price_in_cents);
        $this->discount_code = $discount->getCode();
        $this->discount_type = $discount->getType();
        $this->discount_value = $discount->getValue();
        $this->save();
        $discount->use();


    }

    public function customer(): Customer
    {
        if($this->member) {
            $profile = $this->member->profile;
            return new Customer($profile->full_name, $profile->email, $profile->phone);
        }

        return new Customer($this->customerFullname(), $this->email, $this->phone);
    }

    public function orderedKits()
    {
        return $this->hasMany(OrderedKit::class);
    }

    public function addKit(Kit $kit): OrderedKit
    {
        return OrderedKit::new($this, $kit);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function acceptPayment(PayfastITN $itn)
    {
        $this->is_paid = true;
        $this->status = self::STATUS_OPEN;
        $this->save();

        $this->payment()->create([
            'merchant'     => 'payfast',
            'payment_id'   => $itn->payment_id,
            'amount_gross' => $itn->amount_gross,
            'amount_fee'   => $itn->amount_fee,
            'amount_net'   => $itn->amount_net,
            'item'         => $itn->item,
            'description'  => $itn->description,
        ]);
    }

    public function isPaid(): bool
    {
        return !!$this->payment;
    }

    public function fullDelete()
    {
        $this->orderedKits()->delete();
        $this->delete();
    }

    public function updateState()
    {
        if ($this->orderedKits->every(fn(OrderedKit $kit) => $kit->isDone())) {
            $this->markAsComplete();
        }
    }


    private function markAsComplete()
    {
        $this->status = self::STATUS_COMPLETE;
        $this->save();
    }

    public function summarizeForAdmin(): array
    {
        return [
            'id'                => $this->id,
            'date'              => DatePresenter::pretty($this->created_at),
            'customer_fullname' => $this->customerFullname(),
            'price'             => sprintf("R%s", round($this->price_in_cents / 100, 2)),
            'status'            => $this->status,
            'number_of_kits'    => $this->orderedKits()->count(),
            'batch'             => sprintf("Week %s, %s", $this->created_at->week,
                DatePresenter::range($this->created_at->startOfWeek(), $this->created_at->endOfWeek()))
        ];
    }

    public function getOrderDateAttribute(): string
    {
        return DatePresenter::pretty($this->created_at);
    }

    public function presentForAdmin(): array
    {
        return array_merge($this->summarizeForAdmin(), [
            'kits'     => $this->orderedKits->map->summarizeForAdmin()->toArray(),
            'customer' => $this->customer()->toArray(),
        ]);
    }

    public function notifyCustomerAwaitingConfirmation()
    {
        if (!$this->confirmation_sent) {
            Mail::to($this->email)->queue(new AwaitingPaymentConfirmation($this));

            $this->markNotificationSent();
        }
    }

    public function markNotificationSent()
    {
        $this->confirmation_sent = true;
        $this->save();
    }
}
