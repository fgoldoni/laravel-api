<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Modules\Events\Entities\Event;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'name'             => $faker->name,
        'title'            => $faker->name,
        'description'      => $faker->sentence(20),
        'content'          => $faker->sentence(20),
        'city'             => $faker->city,
        'address'          => $faker->address,
        'contact_phone'    => $faker->phoneNumber,
        'contact_email'    => $faker->companyEmail,
        'contact_name'     => $faker->name,
        'start'            => $faker->dateTimeThisMonth,
        'end'              => $faker->dateTimeThisMonth,
        'url'              => $faker->url,
        'color'            => $faker->hexColor,
        'all_day'          => $faker->boolean,
        'online'           => $faker->boolean,
        'user_id'          => $faker->numberBetween(1, 3),
        'eventable_type'   => '',
        'eventable_id'     => 0,
    ];
});
