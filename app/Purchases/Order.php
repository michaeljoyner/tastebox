<?php

namespace App\Purchases;

use App\Meals\Meal;
use App\Orders\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'order_key', 'price_in_cents'];

    protected $casts = ['is_paid' => 'boolean'];

    public static function makeNew(array $customer, $price)
    {
        return static::create([
            'first_name' => $customer['first_name'],
            'last_name' => $customer['last_name'],
            'email' => $customer['email'] ?? '',
            'phone' => $customer['phone'] ?? 'not given',
            'price_in_cents' => $price * 100,
            'order_key' => Str::uuid()->toString(),
        ]);
    }

    public function orderedKits()
    {
        return $this->hasMany(OrderedKit::class);
    }

    public function addKit(Kit $kit, Address $address): OrderedKit
    {
        $menu = Menu::find($kit->menu_id);
        $meals = Meal::find($kit->meals->pluck('id'));

        $ordered_kit = $this->orderedKits()->create([
            'kit_id' => $kit->id,
            'menu_id' => $kit->menu_id,
            'menu_week_number' => $menu->current_from->week,
            'delivery_date' => $menu->delivery_from,
            'meal_summary' => $meals->map(fn (Meal $meal) => [
                'id' => $meal->id,
                'name' => $meal->name,
                'servings' => $kit->meals->first(fn ($m) => $m['id'] === $meal->id)['servings'],
            ])->values()->all(),
            'line_one' => $address->line_one,
            'line_two' => $address->line_two,
            'city' => $address->city,
            'postal_code' => $address->postal_code,
            'delivery_notes' => $address->notes,
        ]);

        $ordered_kit->meals()->sync(
            $kit->meals->mapWithKeys(fn ($m) => [$m['id'] => ['servings' => $m['servings']]])
        );

        return $ordered_kit;

    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function acceptPayment(PayfastITN $itn)
    {
        $this->is_paid = true;
        $this->save();

        $this->payment()->create([
            'merchant' => 'payfast',
            'payment_id' => $itn->payment_id,
            'amount_gross' => $itn->amount_gross,
            'amount_fee' => $itn->amount_fee,
            'amount_net' => $itn->amount_net,
            'item' => $itn->item,
            'description' => $itn->description,
        ]);
    }
}
