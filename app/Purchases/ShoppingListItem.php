<?php


namespace App\Purchases;


use Illuminate\Support\Str;

class ShoppingListItem
{

    public int $id;
    public string $item_name;
    public array $quantities;
    public array $uses;

    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'];
        $this->item_name = $attributes['description'];
        $this->quantities = static::assignQuatinty($attributes['quantity'], $attributes['servings']);
        $this->uses = [static::formatUse($attributes)];
    }

    private static function assignQuatinty(string $quantity, int $servings)
    {
        $qty = (string)Str::of($quantity)->replace(' ', '');

        $matches = [];
        preg_match('/[a-zA-Z]*$/', $qty, $matches);

        $unit = $matches[0] ?? false ? $matches[0] : 'x_unit';

        return [Str::lower($unit) => floatval($qty) * $servings / 4];
    }

    public static function formatUse($attributes)
    {
        if($attributes['form']) {
            return sprintf("%s servings of %s (%s %s)", $attributes['servings'], $attributes['meal'], $attributes['quantity'], $attributes['form']);
        }

        return sprintf("%s servings of %s (%s)", $attributes['servings'], $attributes['meal'], $attributes['quantity']);
    }

    public function mergeWith(ShoppingListItem $item)
    {
        $unit = array_key_first($item->quantities);
        if(array_key_exists($unit, $this->quantities)) {
            $this->quantities[$unit] = $this->quantities[$unit] + $item->quantities[$unit];
        } else {
            $this->quantities[$unit] = $item->quantities[$unit];
        }

        array_push($this->uses, $item->uses[0]);
    }

    public function toArray()
    {
        return [
            'id'        => $this->id,
            'item_name' => $this->item_name,
            'amounts'   => $this->quantities,
            'uses'      => $this->uses,
        ];
    }
}
