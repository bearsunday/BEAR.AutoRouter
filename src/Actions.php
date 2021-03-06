<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use AutoRoute\Actions as Origin;

use function rtrim;

class Actions extends Origin
{
    public function getClass(
        string $verb,
        string $subNamespace,
        string $tail = ''
    ): ?string {
        unset($verb);
        $base = rtrim($this->config->namespace, '\\')
            . $subNamespace;

        if ($tail !== '') {
            $base .= $tail . '\\';
        }

        return $base;
    }
}
