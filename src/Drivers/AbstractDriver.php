<?php

declare(strict_types=1);

namespace Tims\Foodlite\Drivers;

use Tims\Foodlite\Contracts\Driver;

abstract class AbstractDriver implements Driver
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected array $config = [],
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
