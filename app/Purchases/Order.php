<?php

namespace App\Purchases;

use App\DatePresenter;
use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{

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
    ];

    protected $casts = ['is_paid' => 'boolean'];

    public function customerFullname(): string
    {
        return trim(sprintf("%s %s", $this->first_name, $this->last_name));
    }

    public static function makeNew(array $customer, $price): Order
    {
        return static::create([
            'first_name'     => $customer['first_name'],
            'last_name'      => $customer['last_name'],
            'email'          => $customer['email'] ?? '',
            'phone'          => $customer['phone'] ?? 'not given',
            'price_in_cents' => $price * 100,
            'order_key'      => Str::uuid()->toString(),
            'status'         => self::STATUS_PENDING,
        ]);
    }

    public function customer(): Customer
    {
        return new Customer($this->customerFullname(), $this->email, $this->phone);
    }

    public function orderedKits()
    {
        return $this->hasMany(OrderedKit::class);
    }

    public function addKit(Kit $kit, Address $address): OrderedKit
    {
        return OrderedKit::new($this, $kit, $address);
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

    public function presentForAdmin(): array
    {
        return array_merge($this->summarizeForAdmin(), [
            'kits' => $this->orderedKits->map->summarizeForAdmin()->toArray(),
            'customer' => $this->customer()->toArray(),
        ]);
    }
}
