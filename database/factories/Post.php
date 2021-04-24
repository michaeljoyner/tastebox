<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Blog\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title'       => $faker->words(3, true),
        'intro'     => $faker->paragraph,
        'description'       => $faker->sentence,
        'body'       => $faker->paragraphs(5, true),
        'first_published'       => now()->subDays($faker->numberBetween(3,100)),
        'is_public'       => $faker->boolean,
    ];
});

$factory->state(Post::class, 'draft', [
    'first_published'       => null,
    'is_public'       => false,
]);

$factory->state(Post::class, 'public', [
    'is_public'       => true,
    'first_published' => now()->subDay(),
]);
