<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DeliveryArea;
use App\Memberships\MemberProfile;
use App\User;
use Faker\Generator as Faker;

$factory->define(MemberProfile::class, function (Faker $faker) {
    return [
        'user_id'          => factory(User::class)->state('member'),
        'first_name'       => $faker->firstName,
        'last_name'        => $faker->lastName,
        'email'            => $faker->email,
        'phone'            => $faker->phoneNumber,
        'address_line_one' => $faker->streetAddress,
        'address_line_two' => '',
        'address_city'     => $faker->randomElement([DeliveryArea::HILTON, DeliveryArea::HOWICK]),
    ];
});

$factory->state(MemberProfile::class, 'complete', []);

$factory->state(MemberProfile::class, 'incomplete', [
    'email'            => '',
    'phone'            => '',
    'address_line_one' => '',
    'address_line_two' => '',
    'address_city'     => '',
]);

