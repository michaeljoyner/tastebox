<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Purchases\Adjustment;
use App\Purchases\Order;
use App\User;
use Faker\Generator as Faker;

$factory->define(Adjustment::class, function (Faker $faker) {
    $resolved = $faker->boolean;

    return [
        'value_in_cents' => $faker->numberBetween(-150, 150) * 100,
        'order_id'       => factory(Order::class),
        'reason'         => $faker->sentence,
        'status'         => $resolved ? Adjustment::STATUS_RESOLVED : Adjustment::STATUS_UNRESOLVED,
        'created_by'     => factory(User::class),
        'resolved_by'    => $resolved ? factory(User::class) : null,
        'user_id'        => null,
        'customer_name'  => $faker->name,
        'customer_email' => $faker->email,
        'customer_phone' => $faker->phoneNumber,
    ];
});

$factory->state(Adjustment::class, 'unresolved', [
    'status' => Adjustment::STATUS_UNRESOLVED,
    'resolved_by' => null,
]);


