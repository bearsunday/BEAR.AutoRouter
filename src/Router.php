<?php

/**
 * This file is part of AutoRoute for PHP.
 */

declare(strict_types=1);

namespace BEAR\AutoRouter;

use AutoRoute\Router as Origin;

use function array_shift;
use function reset;

class Router extends Origin
{
    protected function captureNextSegment(): void
    {
        $subNamespace = '';

        // capture next segment as a subnamespace
        if (! empty($this->segments)) {
            $segment = $this->segmentToNamespace(reset($this->segments));
            $this->log("candidate namespace segment: {$segment}");
            $subNamespace = $this->subNamespace . '\\' . $segment;
        }

        $this->log("subnamespace: {$subNamespace}");
        $this->subNamespace = $subNamespace;
        array_shift($this->segments);
        $this->captureMainClass();
    }
}
