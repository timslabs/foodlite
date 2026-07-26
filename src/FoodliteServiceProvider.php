<?php

declare(strict_types=1);

namespace Tims\Foodlite;

use Illuminate\Support\ServiceProvider;

class FoodliteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/foodlite.php', 'foodlite');

        $this->app->singleton(FoodliteManager::class, function ($app) {
            return new FoodliteManager($app);
        });

        $this->app->alias(FoodliteManager::class, 'foodlite');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/foodlite.php' => config_path('foodlite.php'),
            ], 'foodlite-config');
        }
    }

    /**
     * @return list<string>
     */
    public function provides(): array
    {
        return [
            FoodliteManager::class,
            'foodlite',
        ];
    }
}
