<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AddOns\AddOn;
use App\AddOns\AddOnCategory;
use Faker\Generator as Faker;

$factory->define(AddOn::class, function(Faker $faker) {
    return [
        "add_on_category_id" => factory(AddOnCategory::class),
        "name" => $faker->words(3, true),
        "description" => $faker->sentence,
    ];
});
