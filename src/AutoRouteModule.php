<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use BEAR\Sunday\DispatcherInterface;
use BEAR\Sunday\Extension\Router\RouterInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AutoRouteModule extends AbstractModule
{
    public function __construct(?AbstractModule $module = null)
    {
        parent::__construct($module);
    }

    protected function configure(): void
    {
        $this->bind(RouterInterface::class)->to(AutoRouter::class)->in(Scope::SINGLETON);
        $this->bind(DispatcherInterface::class)->to(AutoDispatchr::class);
    }
}
