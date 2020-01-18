<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Modules\Tickets\Entities\Ticket;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'name'                => $faker->name,
        'offer_1'             => $faker->name,
        'offer_2'             => $faker->name,
        'offer_3'             => $faker->name,
        'price'               => $faker->numberBetween(1, 100),
        'quantity'            => $faker->numberBetween(1, 100),
        'online'              => $faker->boolean,
        'user_id'             => $faker->numberBetween(1, 3),
        'event_id'            => $faker->numberBetween(1, 3),
    ];
});
