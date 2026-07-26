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
    | Multiple drivers can still be used in the same request via
    | Foodlite::driver('name') or Foodlite::zomato() / Foodlite::zomatoPos().
    |
    */

    'default' => env('FOODLITE_DRIVER', 'zomato'),

    /*
    |--------------------------------------------------------------------------
    | Foodlite Drivers
    |--------------------------------------------------------------------------
    |
    | Configure every food API driver used by your application. Each entry
    | wraps a dedicated PHP SDK published on Packagist.
    |
    | - zomato      → tims/zomato-php-sdk (Restaurant API v2.1)
    | - zomato_pos  → tims/zomato-pos-php-sdk (POS Integration APIs)
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

        'zomato_pos' => [
            'api_key' => env('ZOMATO_POS_API_KEY'),
            'base_url' => env('ZOMATO_POS_BASE_URL'),
            'api_key_header' => env('ZOMATO_POS_API_KEY_HEADER', 'api-key'),
            'guzzle' => [
                // 'timeout' => 30,
            ],
        ],

    ],

];
