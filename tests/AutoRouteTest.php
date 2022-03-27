<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use BEAR\AutoRouter\Resource\App\BarItem;
use BEAR\AutoRouter\Resource\App\FooItem;
use BEAR\AutoRouter\Resource\App\FooItem\Edit;
use BEAR\AutoRouter\Resource\App\Index;
use BEAR\AutoRouter\Resource\App\VarItem;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class AutoRouteTest extends TestCase
{
    protected AutoRoute $autoRoute;

    protected function setUp(): void
    {
        $this->autoRoute = new AutoRoute(
            'BEAR\AutoRouter\Resource\App',
            __DIR__ . '/Fake/Resource/App/',
            method: 'onGet'
        );
    }

    public function testAutoRouteWithoutParam(): void
    {
        $router = $this->autoRoute->getRouter();
        $route = $router->route('', '/bar-item');
        $this->assertSame(null, $route->error);
        $this->assertSame(BarItem::class, $route->class);
        $this->assertSame($route->method, 'onGet');
        $this->assertSame([], $route->arguments);
    }

    public function testAutoRouteWithParam(): void
    {
        $router = $this->autoRoute->getRouter();
        $route = $router->route('', '/foo-item/1');
        $this->assertSame(FooItem::class, $route->class);
        $this->assertSame($route->method, 'onGet');
        $this->assertSame(['id' => 1], $route->arguments);
    }

    public function testAutoRouteSubdirectory(): void
    {
        $router = $this->autoRoute->getRouter();
        $route = $router->route('', '/foo-item/1/edit');
        $this->assertSame(Edit::class, $route->class);
        $this->assertSame($route->method, 'onGet');
        $this->assertSame(['id' => 1], $route->arguments);
    }

    public function testAutoRouteSubdirectoryWithAdditnalInvalidParam(): void
    {
        $router = $this->autoRoute->getRouter();
        $route = $router->route('', '/foo-item/1/edit/3');
        // The router have a exception for unknown "3"
        $this->assertInstanceOf(UnexpectedValueException::class, $route->exception);
        // But not suspended.
        $this->assertSame(Edit::class, $route->class);
        $this->assertSame($route->method, 'onGet');
        $this->assertSame(['id' => 1], $route->arguments);
    }

    public function testIndex(): void
    {
        $router = $this->autoRoute->getRouter();
        $route = $router->route('', '/Index');
        $this->assertSame(Index::class, $route->class);
        $this->assertSame($route->method, 'onGet');
        $this->assertSame([], $route->arguments);
    }

    public function testVariadicParams(): void
    {
        $router = $this->autoRoute->getRouter();
        $route = $router->route('', '/var-item/1/2/3');
        $this->assertSame(VarItem::class, $route->class);
        $this->assertSame($route->method, 'onGet');
        $this->assertSame([0 => '1', 1 => '2', 2 => '3'], $route->arguments);
    }
}
