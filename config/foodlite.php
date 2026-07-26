<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Foodlite Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default food API driver that will be used by the
    | package when Foodlite::driver() is called without a name. You may still
    | resolve any configured driver explicitly, Socialite-style:
    |
    |   Foodlite::driver('zomato')
    |   Foodlite::driver('zomato-pos')
    |
    */

    'default' => env('FOODLITE_DRIVER', 'zomato'),

    /*
    |--------------------------------------------------------------------------
    | Foodlite Drivers
    |--------------------------------------------------------------------------
    |
    | Configure credentials for each food API provider your application uses.
    | Each driver wraps a dedicated PHP SDK published on Packagist.
    |
    | - zomato      → tims/zomato-php-sdk (Restaurant API v2.1)
    | - zomato-pos  → tims/zomato-pos-php-sdk (POS Integration APIs)
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

        'zomato-pos' => [
            'api_key' => env('ZOMATO_POS_API_KEY'),
            'base_url' => env('ZOMATO_POS_BASE_URL'),
            'api_key_header' => env('ZOMATO_POS_API_KEY_HEADER', 'api-key'),
            'guzzle' => [
                // 'timeout' => 30,
            ],
        ],

    ],

];
