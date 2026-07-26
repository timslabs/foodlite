<?php

declare(strict_types=1);

namespace Tims\Foodlite\Tests;

use InvalidArgumentException;
use Tims\Foodlite\Contracts\Driver;
use Tims\Foodlite\Drivers\AbstractDriver;
use Tims\Foodlite\Drivers\ZomatoDriver;
use Tims\Foodlite\Facades\Foodlite;
use Tims\Foodlite\FoodliteManager;
use Tims\Zomato\Http\HttpClient;
use Tims\Zomato\ZomatoClient;

class FoodliteManagerTest extends TestCase
{
    public function test_default_driver_is_zomato(): void
    {
        $driver = Foodlite::driver();

        $this->assertInstanceOf(ZomatoDriver::class, $driver);
        $this->assertSame('zomato', $driver->getName());
    }

    public function test_driver_resolves_zomato_explicitly(): void
    {
        $driver = Foodlite::driver('zomato');

        $this->assertInstanceOf(ZomatoDriver::class, $driver);
        $this->assertInstanceOf(Driver::class, $driver);
    }

    public function test_zomato_driver_builds_sdk_client(): void
    {
        /** @var ZomatoDriver $driver */
        $driver = Foodlite::driver('zomato');
        $client = $driver->client();

        $this->assertInstanceOf(ZomatoClient::class, $client);
        $this->assertSame(HttpClient::BASE_URL, $client->http()->getBaseUrl());
        $this->assertSame('test-user-key', $client->http()->getUserKey());
    }

    public function test_zomato_driver_respects_custom_base_url(): void
    {
        config([
            'foodlite.drivers.zomato.base_url' => 'https://example.test/api/v2.1',
        ]);

        // Forget cached manager/driver so config change applies.
        $this->app->forgetInstance(FoodliteManager::class);
        $this->app->singleton(FoodliteManager::class, fn ($app) => new FoodliteManager($app));
        Foodlite::clearResolvedInstances();

        /** @var ZomatoDriver $driver */
        $driver = Foodlite::driver('zomato');

        $this->assertSame(
            'https://example.test/api/v2.1',
            $driver->client()->http()->getBaseUrl(),
        );
    }

    public function test_unknown_driver_throws(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Foodlite::driver('does-not-exist');
    }

    public function test_extend_registers_custom_driver(): void
    {
        Foodlite::extend('custom', function () {
            return new class extends AbstractDriver
            {
                public function getName(): string
                {
                    return 'custom';
                }
            };
        });

        $driver = Foodlite::driver('custom');

        $this->assertSame('custom', $driver->getName());
    }
}
