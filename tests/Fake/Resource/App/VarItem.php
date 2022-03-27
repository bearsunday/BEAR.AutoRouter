<?php

declare(strict_types=1);

namespace BEAR\AutoRouter\Resource\App;

use BEAR\Resource\ResourceObject;

class VarItem extends ResourceObject
{
    public function onGet(...$args): static
    {
        return $this;
    }
}
