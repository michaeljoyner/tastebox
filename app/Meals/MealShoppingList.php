<?php

namespace App\Meals;

use App\MealShoppingListEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Concerns\HasUuid;

class MealShoppingList extends Model
{
    use HasFactory, HasUuid;

    protected $guarded = [];

    public static function fromMealList(Collection $mealList): static
    {
        $list = static::create();

        $mealList->each(
            fn ($meal) => $list->entries()->create([
                'meal_id' => $meal['meal_id'],
                'servings' => $meal['qty'],
            ])
        );

        return $list;
    }

    public function entries(): HasMany
    {
        return $this->hasMany(MealShoppingListEntry::class);
    }
}
