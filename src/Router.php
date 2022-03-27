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

        $this->log("find subnamespace: {$subNamespace}");

        // does the subnamespace exist?
        if ($this->actions->hasSubNamespace($subNamespace)) {
            $ns = rtrim($this->config->namespace, '\\') . $subNamespace;
            $this->log('subnamespace not found');

            // are we are the very top of the url?
            if ($this->subNamespace === '') {
                // yes, try to capture arguments for it
                $this->captureRootClass();
                return;
            }

            // no, so no need to keep matching
            throw new Exception\NotFound("Not a known namespace: {$ns}");
        }

        $this->log('subnamespace found');
        $this->subNamespace = $subNamespace;
        array_shift($this->segments);
        $this->captureMainClass();
    }
}
