<?php

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, static function (Faker $faker) {
    return [
        'first_name'                    => $faker->name,
        'last_name'                     => $faker->name,
        'email'                         => $faker->unique()->safeEmail,
        'dob'                           => $faker->dateTimeThisMonth,
        'gender'                        => $faker->randomElement(['male', 'female ']),
        'country'                       => $faker->country,
        'company'                       => $faker->company,
        'department'                    => $faker->randomElement(['sales', 'development', 'management', 'it']),
        'mobile'                        => $faker->phoneNumber,
        'website'                       => $faker->url,
        'languages_known'               => $faker->randomElement(['English', 'French', 'Germany', 'Arabic']),
        'contact_options'               => $faker->randomElement(['email', 'message', 'phone']),
        'password'                      => '$2y$10$rHtbJP6GQLJdCcpIm6gbUeuyw5RjJWdHTBEM1jNbuuViJ4chi8jdC',
        'email_verified_at'             => $faker->randomElement([now(), null]),
        'api_token'                     => Str::random(60),
        'remember_token'                => Str::random(10),
    ];
});
