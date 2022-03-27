<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use BEAR\AppMeta\Meta;
use PHPUnit\Framework\TestCase;

class AuraRouterTest extends TestCase
{
    /** @var AutoRouter */
    private $router;

    protected function setUp(): void
    {
        $meta = new Meta('BEAR\AutoRouter', 'app', __DIR__ . '/Fake');
        $this->router = new AutoRouter($meta, 'app://self');
    }

    public function testRoute(): void
    {
        $globals = [
            '_GET' => [],
            '_POST' => [],
        ];
        $server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => 'http://localhost/bar-item',
        ];
        $request = $this->router->match($globals, $server);
        $this->assertSame('get', $request->method);
        $this->assertSame('app://self/BarItem', $request->path);
        $this->assertSame([], $request->query);
    }

    public function testRouteWithParam(): void
    {
        $globals = [
            '_GET' => [],
            '_POST' => [],
        ];
        $server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => 'http://localhost/foo-item/1',
        ];
        $request = $this->router->match($globals, $server);
        $this->assertSame('get', $request->method);
        $this->assertSame('app://self/FooItem', $request->path);
        $this->assertSame(['id' => 1], $request->query);
    }
}
