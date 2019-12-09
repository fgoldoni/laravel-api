<?php

declare(strict_types=1);

return [

    // Categories Database Tables
    'tables' => [

        'categories' => 'categories',
        'categorizables' => 'categorizables',

    ],

    // Categories Models
    'models' => [
        'category' => \Modules\Categories\Entities\Category::class,
    ],

];
