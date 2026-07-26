<?php

declare(strict_types=1);

namespace Tims\Foodlite\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tims\Foodlite\Facades\Foodlite;
use Tims\Foodlite\FoodliteServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            FoodliteServiceProvider::class,
        ];
    }

    /**
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Foodlite' => Foodlite::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('foodlite.default', 'zomato');
        $app['config']->set('foodlite.drivers.zomato', [
            'user_key' => 'test-user-key',
            'base_url' => null,
            'guzzle' => [],
        ]);
        $app['config']->set('foodlite.drivers.zomato-pos', [
            'api_key' => 'test-pos-api-key',
            'base_url' => null,
            'api_key_header' => 'api-key',
            'guzzle' => [],
        ]);
    }
}
