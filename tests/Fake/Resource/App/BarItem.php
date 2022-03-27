<?php

declare(strict_types=1);

namespace BEAR\AutoRouter\Resource\App;

class BarItem
{
    public function onGet(): static
    {
        return $this;
    }
}
