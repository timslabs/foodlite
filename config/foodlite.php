<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Foodlite Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default food API driver that will be used by
    | the package. You may set this to any of the drivers defined below.
    |
    */

    'default' => env('FOODLITE_DRIVER', 'zomato'),

    /*
    |--------------------------------------------------------------------------
    | Foodlite Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure every food API driver used by your application.
    | Each driver wraps a dedicated PHP SDK (e.g. tims/zomato-php-sdk).
    |
    */

    'drivers' => [

        'zomato' => [
            'user_key' => env('ZOMATO_USER_KEY'),
            'base_url' => env('ZOMATO_BASE_URL'),
            'guzzle' => [
                // 'timeout' => 30,
            ],
        ],

    ],

];
