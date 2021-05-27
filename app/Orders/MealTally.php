<?php

namespace App\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealTally extends Model
{
    protected $guarded = [];

    protected $casts = [
        'times_offered' => 'integer',
        'total_ordered' => 'integer',
        'total_servings' => 'integer',
        'last_offered' => 'date',
    ];

    public static function forBatch(BatchMealsTally $mealsTally)
    {
        $mealsTally->meals->each(fn($meal) => self::tallyMeal($meal, $mealsTally->date));
    }

    private static function tallyMeal(array $meal, $date)
    {
        $tally = self::where('meal_id', $meal['id'])->first();
        if(!$tally) {
            return self::create([
                'meal_id' => $meal['id'],
                'times_offered' => 1,
                'total_ordered' => $meal['total_ordered'],
                'total_servings' => $meal['total_servings'],
                'last_offered' => $date,
            ]);
        }

        $tally->update([
            'times_offered' => $tally->times_offered + 1,
            'total_ordered' => $tally->total_ordered +$meal['total_ordered'],
            'total_servings' => $tally->total_servings + $meal['total_servings'],
            'last_offered' => $date,
        ]);

    }
}
