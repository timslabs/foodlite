<?php

declare(strict_types=1);

namespace Tims\Foodlite\Facades;

use Illuminate\Support\Facades\Facade;
use Tims\Foodlite\Drivers\ZomatoDriver;
use Tims\Foodlite\Drivers\ZomatoPosDriver;
use Tims\Foodlite\FoodliteManager;

/**
 * @method static \Tims\Foodlite\Contracts\Driver driver(?string $driver = null)
 * @method static ZomatoDriver zomato()
 * @method static ZomatoPosDriver zomatoPos()
 * @method static string getDefaultDriver()
 * @method static \Tims\Foodlite\FoodliteManager extend(string $driver, \Closure $callback)
 *
 * @see FoodliteManager
 * @see ZomatoDriver
 * @see ZomatoPosDriver
 */
class Foodlite extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FoodliteManager::class;
    }
}
