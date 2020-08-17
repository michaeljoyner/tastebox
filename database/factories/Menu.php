<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Orders\Menu;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Menu::class, function (Faker $faker) {
    return [
        'current_from' => Carbon::today()->addWeek()->startOfWeek(),
        'current_to' => Carbon::today()->addWeek()->startOfWeek()->addDays(5),
        'delivery_from' => Carbon::today()->addWeek()->endOfWeek()->addDay(),
    ];
});

$factory->state(Menu::class, 'old', [
    'current_from' => Carbon::today()->subWeeks(3)->startOfWeek(),
    'current_to' => Carbon::today()->subWeeks(3)->startOfWeek()->addDays(5),
    'delivery_from' => Carbon::today()->subWeeks(3)->endOfWeek()->addDay(),
]);

$factory->state(Menu::class, 'current', [
    'current_from' => Carbon::today()->startOfWeek(),
    'current_to' => Carbon::today()->startOfWeek()->addDays(5),
    'delivery_from' => Carbon::today()->endOfWeek()->addDay(),
    'can_order' => true,
]);

$factory->state(Menu::class, 'upcoming', [
    'current_from' => Carbon::today()->addWeeks(2)->startOfWeek(),
    'current_to' => Carbon::today()->addWeeks(2)->startOfWeek()->addDays(5),
    'delivery_from' => Carbon::today()->addWeeks(2)->endOfWeek()->addDay(),
    'can_order' => true,
]);

$factory->state(Menu::class, 'closed', [
    'can_order' => false,
    'current_from' => Carbon::today()->addWeeks(2)->startOfWeek(),
    'current_to' => Carbon::today()->addWeeks(2)->startOfWeek()->addDays(5),
    'delivery_from' => Carbon::today()->addWeeks(2)->endOfWeek()->addDay(),
]);

$factory->state(Menu::class, 'open', [
    'can_order' => true,
    'current_from' => Carbon::today()->addWeeks(2)->startOfWeek(),
    'current_to' => Carbon::today()->addWeeks(2)->startOfWeek()->addDays(5),
    'delivery_from' => Carbon::today()->addWeeks(2)->endOfWeek()->addDay(),
]);
