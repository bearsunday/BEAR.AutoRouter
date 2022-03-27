<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use BEAR\AppMeta\Meta;
use BEAR\AutoRouter\Resource\App\BarItem;
use BEAR\AutoRouter\Resource\App\FooItem;
use BEAR\AutoRouter\Resource\App\Index;
use BEAR\Resource\Module\ResourceModule;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class AuraDispatcherTest extends TestCase
{
    /** @var AutoDispatchr */
    private $router;

    protected function setUp(): void
    {
        $injector = new Injector(new ResourceModule('BEAR\AutoRouter'));
        $meta = new Meta('BEAR\AutoRouter', 'app', __DIR__ . '/Fake');
        $this->router = new AutoDispatchr($injector, $meta, 'app://self');
    }

    public function testRoute(): void
    {
        $server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => 'http://localhost/bar-item',
        ];
        $invoke = $this->router->route($server);
        $this->assertSame('onget', $invoke->method);
        $this->assertSame(BarItem::class, $invoke->class);
        $this->assertSame([], $invoke->arguments);
    }

    public function testRouteWithParam(): void
    {
        $server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => 'http://localhost/foo-item/1',
        ];
        $invoke = $this->router->route($server);
        $this->assertSame('onget', $invoke->method);
        $this->assertSame(FooItem::class, $invoke->class);
        $this->assertSame(['id' => 1], $invoke->arguments);
        $ro = $invoke();
        $this->assertInstanceOf(FooItem::class, $ro);
        $this->assertInstanceOf(FooItem::class, $ro);
    }

    public function testAutoRouteSubdirectory(): void
    {
        $server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => 'http://localhost/foo-item/1/edit',
        ];
        $invoke = $this->router->route($server);
        $this->assertSame('onget', $invoke->method);
        $this->assertSame(FooItem\Edit::class, $invoke->class);
        $this->assertSame(['id' => 1], $invoke->arguments);
    }

    public function testIndex(): void
    {
        $server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => 'http://localhost/',
        ];
        $invoke = $this->router->route($server);
        $this->assertSame('onget', $invoke->method);
        $this->assertSame(Index::class, $invoke->class);
        $this->assertSame([], $invoke->arguments);
    }
}
