<?php

declare(strict_types=1);

namespace BEAR\AutoRouter\Resource\App;

class Index
{
    public function onGet(int $id): static
    {
        return $this;
    }
}
