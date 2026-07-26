<?php

declare(strict_types=1);

namespace Tims\Foodlite\Contracts;

interface Driver
{
    /**
     * Driver name as registered in config (e.g. "zomato").
     */
    public function getName(): string;
}
