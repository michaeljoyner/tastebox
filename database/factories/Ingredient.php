<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Meals\Ingredient as Ingredient;
use Faker\Generator as Faker;

$factory->define(Ingredient::class, function (Faker $faker) {
    return [
        'description' => $faker->words(2, true),
    ];
});
