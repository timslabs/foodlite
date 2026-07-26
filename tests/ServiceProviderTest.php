<?php

declare(strict_types=1);

namespace Tims\Foodlite\Tests;

use Tims\Foodlite\Facades\Foodlite;
use Tims\Foodlite\FoodliteManager;
use Tims\Foodlite\FoodliteServiceProvider;

class ServiceProviderTest extends TestCase
{
    public function test_registers_manager_as_singleton(): void
    {
        $a = $this->app->make(FoodliteManager::class);
        $b = $this->app->make(FoodliteManager::class);

        $this->assertSame($a, $b);
        $this->assertSame($a, $this->app->make('foodlite'));
    }

    public function test_facade_resolves_to_manager(): void
    {
        $this->assertInstanceOf(FoodliteManager::class, Foodlite::getFacadeRoot());
    }

    public function test_merges_package_config(): void
    {
        $this->assertSame('zomato', config('foodlite.default'));
        $this->assertSame('test-user-key', config('foodlite.drivers.zomato.user_key'));
    }

    public function test_publishes_config(): void
    {
        $this->artisan('vendor:publish', [
            '--provider' => FoodliteServiceProvider::class,
            '--tag' => 'foodlite-config',
        ])->assertSuccessful();

        $this->assertFileExists(config_path('foodlite.php'));
    }

    public function test_provider_declares_provides(): void
    {
        $provider = new FoodliteServiceProvider($this->app);

        $this->assertSame([
            FoodliteManager::class,
            'foodlite',
        ], $provider->provides());
    }
}
