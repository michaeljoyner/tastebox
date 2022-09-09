<?php

namespace App\Purchases;

use App\DeliveryAddress;
use App\DeliveryArea;
use App\LogsActivities;
use App\Meals\Meal;
use App\Orders\Menu;
use App\User;
use Illuminate\Database\Eloquent\Model;

class OrderedKit extends Model
{

    use LogsActivities;

    const STATUS_DUE = 'due';
    const STATUS_CANCELED = 'cancelled';
    const STATUS_DONE = 'done';

    protected $fillable = [
        'kit_id',
        'menu_id',
        'menu_week_number',
        'delivery_date',
        'meal_summary',
        'line_one',
        'line_two',
        'city',
        'postal_code',
        'delivery_notes',
        'status'
    ];

//    protected $dates = ['delivery_date'];

    protected $casts = [
        'meal_summary'  => 'array',
        'delivery_date' => 'date'
    ];

    public function scopeDue($query)
    {
        return $query->where('status', self::STATUS_DUE);
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class)
                    ->using(OrderedMeal::class)
                    ->withPivot('servings');
    }

    public static function new(Order $order, Kit $kit): self
    {
        $menu = Menu::find($kit->menu_id);
        $meals = Meal::find($kit->meals->pluck('id'));

        $ordered_kit = $order->orderedKits()->create([
            'kit_id'           => $kit->id,
            'menu_id'          => $kit->menu_id,
            'menu_week_number' => $menu->current_from->week,
            'delivery_date'    => $menu->delivery_from,
            'meal_summary'     => [],
            'line_one'         => $kit->delivery_address->address,
            'line_two'         => '',
            'city'             => $kit->delivery_address->area->value,
            'postal_code'      => '',
            'delivery_notes'   => '',
            'status'           => self::STATUS_DUE,
        ]);

        $ordered_kit->setMeals($kit->mealSummary());

        return $ordered_kit;
    }

    public function setMeals(KitMealSummary $mealSummary)
    {
        $this->meal_summary = $mealSummary->toArray();
        $this->save();

        $this->meals()->sync(
            $mealSummary->meals->mapWithKeys(
                fn(KitMeal $meal) => [$meal->meal_id => ['servings' => $meal->servings]]
            )
        );
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function packedAndDelivered()
    {
        $this->status = self::STATUS_DONE;
        $this->save();

        $this->order->updateState();
    }

    public function isDone(): bool
    {
        return $this->status === self::STATUS_DONE;
    }

    public function summarize(): OrderedKitSummary
    {
        return OrderedKitPresenter::summary($this);
    }

    public function summarizeForAdmin()
    {
        return OrderedKitPresenter::adminSummary($this);
    }

    public function deliveryAddress(): DeliveryAddress
    {
        return new DeliveryAddress(
            DeliveryArea::tryFrom($this->city) ?? DeliveryArea::NOT_SET,
            $this->line_one
        );
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function value(): int
    {
        if ($this->meals->count() === 0) {
            return 0;
        }

        return $this->meals->sum(fn($meal) => $meal->pivot->servings * Meal::SERVING_PRICE * 100);
    }

    public function adjustMeals(KitMealSummary $meals, string $reason, User $adjusted_by)
    {
        $original_value = $this->value();

        $this->setMeals($meals);


        Adjustment::new(
            $original_value,
            $this->fresh()->value(),
            $this->order,
            $reason,
            $adjusted_by,
        );

        $this->logActivity(
            $adjusted_by->name,
            sprintf("%s adjusted an ordered kit", $adjusted_by->name),
            "/ordered-kits/{$this->id}/show"
        );
    }

    public function cancel(string $reason, User $user)
    {


        Adjustment::new(
            $this->value(),
            0,
            $this->order,
            $reason,
            $user,
        );

        $this->meals()->sync([]);
        $this->update([
            'status'       => self::STATUS_CANCELED,
            'meal_summary' => [],
        ]);

        $this->logActivity(
            $user->name,
            sprintf("%s cancelled an ordered kit", $user->name),
            "/ordered-kits/{$this->id}/show"
        );
    }
}
