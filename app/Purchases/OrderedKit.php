<?php

namespace App\Purchases;

use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Database\Eloquent\Model;

class OrderedKit extends Model
{

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
        'meal_summary' => 'array',
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

    public static function new(Order $order, Kit $kit, Address $address): self
    {
        $menu = Menu::find($kit->menu_id);
        $meals = Meal::find($kit->meals->pluck('id'));

        $ordered_kit = $order->orderedKits()->create([
            'kit_id'           => $kit->id,
            'menu_id'          => $kit->menu_id,
            'menu_week_number' => $menu->current_from->week,
            'delivery_date'    => $menu->delivery_from,
            'meal_summary'     => [],
            'line_one'         => $address->line_one,
            'line_two'         => $address->line_two,
            'city'             => $address->city,
            'postal_code'      => $address->postal_code,
            'delivery_notes'   => $address->notes,
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

    public function deliveryAddress(): Address
    {
        return new Address([
            'line_one'    => $this->line_one,
            'line_two'    => $this->line_two ?? '',
            'city'        => $this->city,
            'postal_code' => $this->postal_code,
        ]);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function value(): int
    {
        if($this->meals->count() === 0) {
            return 0;
        }

        return $this->meals->sum(fn ($meal) => $meal->pivot->servings * Meal::SERVING_PRICE * 100);
    }
}
