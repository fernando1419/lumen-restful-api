<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name'  => $faker->name,
        'email' => $faker->email
    ];
});

/**
 * Factory definition for model Author.
 */
$factory->define(App\Author::class, function ($faker) {
    return [
        'name'  => $faker->name,
        'email' => $faker->email,
        'github' => $faker->url,
        'twitter' => $faker->url,
        'location' => $faker->address,
        'last_article_published' => $faker->sentence
    ];
});
