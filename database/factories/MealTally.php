<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Meals\Meal;
use App\Orders\MealTally;
use Faker\Generator as Faker;

$factory->define(MealTally::class, function (Faker $faker) {
    return [
        'meal_id' => factory(Meal::class),
        'times_offered' => $faker->numberBetween(1,10),
        'total_ordered' => $faker->numberBetween(6,50),
        'total_servings' => $faker->numberBetween(10,100),
        'last_offered' => now()->subWeeks($faker->numberBetween(1,5)),
    ];
});


