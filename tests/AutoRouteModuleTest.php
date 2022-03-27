<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\AppMeta\Meta;
use BEAR\Sunday\Annotation\DefaultSchemeHost;
use BEAR\Sunday\DispatcherInterface;
use BEAR\Sunday\Extension\Router\RouterInterface;
use PHPUnit\Framework\TestCase;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class AutoRouteModuleTest extends TestCase
{
    public function testModule(): void
    {
        $injector = (new Injector(new class extends AbstractModule{
            protected function configure()
            {
                $this->bind(AbstractAppMeta::class)->toInstance(
                    new Meta('BEAR\AutoRouter', 'app', __DIR__ . '/Fake')
                );
                $this->bind()->annotatedWith(DefaultSchemeHost::class)->toInstance('app::self/');
                $this->install(new AutoRouteModule());
            }
        }));
        $this->assertInstanceOf(AutoRouter::class, $injector->getInstance(RouterInterface::class));
        $this->assertInstanceOf(AutoDispatchr::class, $injector->getInstance(DispatcherInterface::class));
    }
}
