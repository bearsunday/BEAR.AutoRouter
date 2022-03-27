<?php

declare(strict_types=1);

namespace BEAR\AutoRouter\Resource\App;

class FooItem
{
    public function onGet(int $id): static
    {
        return $this;
    }
}
