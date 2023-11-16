<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Meals\Costing;
use App\Meals\Meal;
use Faker\Generator as Faker;

$factory->define(Costing::class, function (Faker $faker) {
    return [
        'meal_id'     => factory(Meal::class),
        'cost'        => "R" . number_format($faker->numberBetween(1000, 9999) / 100, 2),
        'tier'        => $faker->randomElement(\App\Meals\MealPriceTier::cases()),
        'note'        => $faker->sentence,
        'date_costed' => now()->subDays($faker->numberBetween(1, 5)),
    ];
});
