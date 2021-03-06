<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Purchases\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'order_key' => \Illuminate\Support\Str::uuid()->toString(),
        'price_in_cents' => $faker->numberBetween(125, 1500) * 100,
        'is_paid' => $faker->boolean,
        'status' => $faker->randomElement([Order::STATUS_PENDING, Order::STATUS_OPEN])
    ];
});

$factory->state(Order::class, 'paid', [
    'is_paid' => true,
    'status' => Order::STATUS_OPEN,
]);

$factory->state(Order::class, 'unpaid', [
    'is_paid' => false,
    'status' => Order::STATUS_PENDING
]);

$factory->state(Order::class, 'created', [
    'is_paid' => false,
    'status' => Order::STATUS_CREATED
]);


