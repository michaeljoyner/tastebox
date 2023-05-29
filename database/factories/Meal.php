<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Meals\Meal;
use Faker\Generator as Faker;

$factory->define(Meal::class, function (Faker $faker) {
    return [
        'unique_id'       => Meal::generateUniqueId(),
        'name'            => $faker->words(3, true),
        'description'     => $faker->paragraph,
        'allergens'       => $faker->sentence,
        'prep_time'       => $faker->numberBetween(10, 60),
        'cook_time'       => $faker->numberBetween(10, 40),
        'instructions'    => $faker->paragraph,
        'serving_energy'  => $faker->numberBetween(0, 300),
        'serving_carbs'   => $faker->numberBetween(0, 100),
        'serving_fat'     => $faker->numberBetween(0, 100),
        'serving_protein' => $faker->numberBetween(0, 100),
        'is_public'       => $faker->boolean,
        'price_tier'      => \App\Meals\MealPriceTier::STANDARD,
    ];
});

$factory->state(Meal::class, 'private', [
    'is_public' => false,
]);

$factory->state(Meal::class, 'public', [
    'is_public' => true,
]);

$factory->state(Meal::class, 'budget', [
    'price_tier' => \App\Meals\MealPriceTier::BUDGET,
]);

$factory->state(Meal::class, 'standard', [
    'price_tier' => \App\Meals\MealPriceTier::STANDARD,
]);

$factory->state(Meal::class, 'premium', [
    'price_tier' => \App\Meals\MealPriceTier::PREMIUM,
]);
