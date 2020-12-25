<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MailingList\MailingListMember;
use Faker\Generator as Faker;

$factory->define(MailingListMember::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});
