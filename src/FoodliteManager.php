<?php

declare(strict_types=1);

namespace Tims\Foodlite;

use Illuminate\Support\Manager;
use InvalidArgumentException;
use Tims\Foodlite\Contracts\Driver;
use Tims\Foodlite\Drivers\ZomatoDriver;
use Tims\Foodlite\Drivers\ZomatoPosDriver;

/**
 * @method Driver driver(?string $driver = null)
 */
class FoodliteManager extends Manager
{
    public function getDefaultDriver(): string
    {
        $driver = $this->config->get('foodlite.default');

        if (! is_string($driver) || $driver === '') {
            throw new InvalidArgumentException('No Foodlite default driver is configured.');
        }

        return $driver;
    }

    /**
     * Resolve the Zomato Restaurant API driver.
     */
    public function zomato(): ZomatoDriver
    {
        /** @var ZomatoDriver $driver */
        $driver = $this->driver('zomato');

        return $driver;
    }

    /**
     * Resolve the Zomato POS Integration API driver.
     */
    public function zomatoPos(): ZomatoPosDriver
    {
        /** @var ZomatoPosDriver $driver */
        $driver = $this->driver('zomato_pos');

        return $driver;
    }

    protected function createZomatoDriver(): ZomatoDriver
    {
        /** @var array<string, mixed> $config */
        $config = $this->config->get('foodlite.drivers.zomato', []);

        return new ZomatoDriver($config);
    }

    protected function createZomatoPosDriver(): ZomatoPosDriver
    {
        /** @var array<string, mixed> $config */
        $config = $this->config->get('foodlite.drivers.zomato_pos', []);

        return new ZomatoPosDriver($config);
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     *
     * @throws InvalidArgumentException
     */
    protected function createDriver($driver): Driver
    {
        $created = parent::createDriver($driver);

        if (! $created instanceof Driver) {
            throw new InvalidArgumentException("Driver [{$driver}] is not a Foodlite driver.");
        }

        return $created;
    }
}
