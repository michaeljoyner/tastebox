<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Purchases\DiscountCode;
use Faker\Generator as Faker;

$factory->define(DiscountCode::class, function (Faker $faker) {
    return [
        'code' => \Illuminate\Support\Str::random(),
        'valid_from' => now(),
        'valid_until' => now()->addWeek(),
        'type' => $faker->randomElement([DiscountCode::LUMP, DiscountCode::PERCENTAGE]),
        'value' => $faker->numberBetween(10, 25),
        'uses' => $faker->numberBetween(10,100),
    ];
});

$factory->state(DiscountCode::class, 'expired', [
    'valid_from' => now()->subWeek(),
    'valid_until' => now()->subDay(),
]);

$factory->state(DiscountCode::class, 'used', [
    'uses' => 0,
]);
