<?php

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, static function (Faker $faker) {
    return [
        'first_name'        => $faker->name,
        'last_name'         => $faker->name,
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password'          => '$2y$10$rHtbJP6GQLJdCcpIm6gbUeuyw5RjJWdHTBEM1jNbuuViJ4chi8jdC',
        'remember_token'    => Str::random(10),
        'api_token'         => Str::random(60),
    ];
});
