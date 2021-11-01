<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Purchases\Discount;
use App\Purchases\MemberDiscount;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(MemberDiscount::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->state('member'),
        'code' => Str::random(),
        'valid_from' => now(),
        'valid_until' => now()->addWeek(),
        'type' => $faker->randomElement([Discount::LUMP, Discount::PERCENTAGE]),
        'value' => $faker->numberBetween(10, 25),
        'discount_tag' => null,
    ];
});

$factory->state(MemberDiscount::class, 'expired', [
    'valid_from' => now()->subWeek(),
    'valid_until' => now()->subDay(),
]);

$factory->state(MemberDiscount::class, 'tagged', [
    'discount_tag' => Str::uuid()->toString(),
]);


