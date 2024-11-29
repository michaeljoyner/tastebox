<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AddOns\AddOnCategory;
use Faker\Generator as Faker;

$factory->define(AddOnCategory::class, function(Faker $faker) {
    return [
        "name" => $faker->words(3, true),
        "description" => $faker->sentence,
    ];
});
