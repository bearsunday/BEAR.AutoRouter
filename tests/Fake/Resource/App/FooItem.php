<?php

declare(strict_types=1);

namespace BEAR\AutoRouter\Resource\App;

use BEAR\Resource\ResourceObject;

class FooItem extends ResourceObject
{
    public int $id;

    public function onGet(int $id): static
    {
        $this->id = $id;

        return $this;
    }
}
