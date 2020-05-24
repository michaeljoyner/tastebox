<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Meals\Classification;
use Faker\Generator as Faker;

$factory->define(Classification::class, function (Faker $faker) {
    return [
        'name' => $faker->words(3, true)
    ];
});

$factory->state(Classification::class, 'veg', [
    'name' => 'vegetarian',
]);

$factory->state(Classification::class, 'kids', [
    'name' => 'kid friendly',
]);

$factory->state(Classification::class, 'meat', [
    'name' => 'meat lovers',
]);
