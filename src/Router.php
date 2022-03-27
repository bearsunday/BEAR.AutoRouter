<?php

/**
 * This file is part of AutoRoute for PHP.
 */

declare(strict_types=1);

namespace BEAR\AutoRouter;

use AutoRoute\Router as Origin;
use ReflectionParameter;

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

    protected function captureArgument(
        ReflectionParameter $parameter,
        int $i
    ): void {
        if (empty($this->segments)) {
            return;
        }

        if ($parameter->isVariadic()) {
            $this->captureVariadic($parameter, $i);

            return;
        }

        $this->arguments[$parameter->name] = $this->filter->parameter(
            $parameter,
            $this->segments
        );
        $name = $parameter->getName();
        $this->log("captured argument {$i} (\${$name})");
    }

    protected function captureVariadic(
        ReflectionParameter $parameter,
        int $i
    ): void {
        $name = $parameter->getName();

        while (! empty($this->segments)) {
            $this->arguments[$parameter->name] = $this->filter->parameter(
                $parameter,
                $this->segments
            );
            $this->log("captured variadic argument {$i} (\${$name})");
        }
    }
}
