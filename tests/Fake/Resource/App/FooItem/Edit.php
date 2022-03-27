<?php

declare(strict_types=1);

namespace BEAR\AutoRouter\Resource\App\FooItem;

use BEAR\Resource\ResourceObject;

class Edit  extends ResourceObject
{
    public function onGet(int $id): static
    {
        return $this;
    }
}
