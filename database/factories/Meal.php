<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Meals\Meal;
use Faker\Generator as Faker;

$factory->define(Meal::class, function (Faker $faker) {
    return [
        'unique_id' => Meal::generateUniqueId(),
        'name'            => $faker->words(3, true),
        'description'     => $faker->paragraph,
        'allergens'       => $faker->sentence,
        'prep_time'       => $faker->numberBetween(10,60),
        'cook_time'       => $faker->numberBetween(10,40),
        'instructions'    => $faker->paragraph,
        'serving_energy'  => $faker->numberBetween(0,300),
        'serving_carbs'   => $faker->numberBetween(0,100),
        'serving_fat'     => $faker->numberBetween(0,100),
        'serving_protein' => $faker->numberBetween(0,100),
    ];
});
