<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Purchases\Payment;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'order_id' => factory(\App\Purchases\Order::class)->state('paid'),
        'merchant' => $faker->company,
        'payment_id' => 9999999,
        'amount_gross' => 8888,
        'amount_fee' => 333,
        'amount_net' => 8467,
        'item' => $faker->word,
        'description' => $faker->sentence,
    ];
});

$factory->state(Payment::class, 'payfast', [
    'merchant' => 'payfast'
]);
