<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use AutoRoute\Router as Origin;
use ReflectionParameter;

use function array_shift;
use function reset;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class Router extends Origin
{
    /**
     * @psalm-suppress MixedArgument
     */
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
}
