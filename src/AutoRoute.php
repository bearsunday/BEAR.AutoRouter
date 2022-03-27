<?php

/**
 * This file is part of AutoRoute for PHP.
 */

declare(strict_types=1);

namespace BEAR\AutoRouter;

use AutoRoute\AutoRoute as Origin;

class AutoRoute extends Origin
{
    public function getActions(): \AutoRoute\Actions
    {
        if ($this->actions === null) {
            $this->actions = new Actions(
                $this->getConfig(),
                $this->getReflector(),
            );
        }

        return $this->actions;
    }

    public function getRouter(): \AutoRoute\Router
    {
        if ($this->router === null) {
            $this->router = new Router(
                $this->getConfig(),
                $this->getActions(),
                $this->getFilter(),
                $this->getLogger(),
            );
        }

        return $this->router;
    }
}
