<?php

declare(strict_types=1);

namespace BEAR\AutoRouter\Resource\App;

use BEAR\Resource\ResourceObject;

class BarItem extends ResourceObject
{
    public function onGet(): static
    {
        return $this;
    }
}
