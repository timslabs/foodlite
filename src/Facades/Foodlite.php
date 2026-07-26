<?php

declare(strict_types=1);

namespace Tims\Foodlite\Facades;

use Illuminate\Support\Facades\Facade;
use Tims\Foodlite\FoodliteManager;

/**
 * @method static \Tims\Foodlite\Contracts\Driver driver(?string $driver = null)
 * @method static string getDefaultDriver()
 * @method static \Tims\Foodlite\FoodliteManager extend(string $driver, \Closure $callback)
 *
 * @see FoodliteManager
 */
class Foodlite extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FoodliteManager::class;
    }
}
