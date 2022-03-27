<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\AppMeta\Meta;
use BEAR\Sunday\Extension\Router\RouterInterface;
use PHPUnit\Framework\TestCase;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class AutoRouteModuleTest extends TestCase
{
    protected function testModule(): void
    {
        $router = (new Injector(new class extends AbstractModule{
            protected function configure()
            {
                $this->bind(AbstractAppMeta::class)->toInstance(
                    new Meta('BEAR\AutoRouter', 'app', __DIR__ . '/Fake')
                );
                $this->install(new AutoRouteModule());
            }
        }))->getInstance(RouterInterface::class);
        $this->assertInstanceOf(AutoRouter::class, $router);
    }
}
