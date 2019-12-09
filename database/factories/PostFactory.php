<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Modules\Posts\Entities\Post;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'name'      => $faker->name,
        'content'   => $faker->sentence(20),
        'online'    => $faker->boolean,
        'user_id'   => $faker->numberBetween(1, 3),
    ];
});
