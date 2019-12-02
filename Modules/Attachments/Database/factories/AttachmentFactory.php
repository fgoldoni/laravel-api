<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;

$factory->define(\Modules\Attachments\Entities\Attachment::class, function (Faker $faker) {
    return [
        'name'                           => 'avatar.jpg',
        'attachable_id'                  => 1,
        'attachable_type'                => 'App\\User',
        'type'                           => 'users',
        'extension'                      => 'png',
        'mime_type'                      => 'image/png',
        'basename'                       => '1575285027.png',
        'author'                         => '',
    ];
});
