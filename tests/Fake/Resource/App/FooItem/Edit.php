<?php

declare(strict_types=1);

namespace BEAR\AutoRouter\Resource\App\FooItem;

class Edit
{
    public function onGet(int $id): static
    {
        return $this;
    }
}
