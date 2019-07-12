<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name'     => $faker->name,
        'email'    => $faker->email,
        'password' => $faker->password
    ];
});

/**
 * Factory definition for Author model.
 */
$factory->define(App\Author::class, function (Faker $faker) {
    return [
        'name'                   => $faker->name,
        'email'                  => $faker->email,
        'github'                 => $faker->url,
        'twitter'                => $faker->url,
        'location'               => $faker->address,
        'last_article_published' => $faker->sentence,
        'some_boolean'           => $faker->boolean()
    ];
});

/**
 * Factory definition for Book model.
 */
$factory->define(App\Book::class, function (Faker $faker) {
    return [
        'title'       => $faker->sentence,
        'description' => $faker->paragraph,
        'isbn'        => $faker->isbn10,
        'author_id'   => $faker->numberBetween(1, 50)
    ];
});
