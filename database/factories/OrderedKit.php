<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Orders\Menu;
use App\Purchases\Order;
use App\Purchases\OrderedKit;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

$factory->define(OrderedKit::class, function (Faker $faker) {
    return [
        'order_id'         => factory(Order::class),
        'kit_id'           => Str::uuid()->toString(),
        'menu_id'          => factory(Menu::class),
        'menu_week_number' => $faker->numberBetween(1, 52),
        'delivery_date'    => Carbon::today()->addWeek(),
        'meal_summary'     => [],
        'line_one'         => $faker->streetAddress,
        'line_two'         => 'fakerton',
        'city'             => $faker->city,
        'postal_code'      => $faker->postcode,
        'delivery_notes'   => $faker->sentence,
        'status'           => $faker->randomElement([
            OrderedKit::STATUS_DONE,
            OrderedKit::STATUS_DUE
        ])
    ];
});

$factory->state(OrderedKit::class, 'due', [
    'status' => OrderedKit::STATUS_DUE,
]);

$factory->state(OrderedKit::class, 'done', [
    'status' => OrderedKit::STATUS_DONE,
]);
