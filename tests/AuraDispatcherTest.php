<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use BEAR\AppMeta\Meta;
use BEAR\AutoRouter\Resource\App\FooItem;
use BEAR\Resource\InvokerInterface;
use BEAR\Resource\Module\ResourceModule;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

use function assert;

class AuraDispatcherTest extends TestCase
{
    /** @var AutoDispatchr */
    private $router;

    protected function setUp(): void
    {
        $injector = new Injector(new ResourceModule('BEAR\AutoRouter'));
        $meta = new Meta('BEAR\AutoRouter', 'app', __DIR__ . '/Fake');
        $invoker = $injector->getInstance(InvokerInterface::class);
        assert($invoker instanceof InvokerInterface);
        $this->router = new AutoDispatchr($injector, $meta, 'app://self', $invoker);
    }

    public function testRoute(): void
    {
        $server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => 'http://localhost/bar-item',
        ];
        $request = $this->router->route($server);
        $this->assertSame('app://self/baritem', $request->toUri());
    }

    public function testRouteWithParam(): void
    {
        $server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => 'http://localhost/foo-item/1',
        ];
        $request = $this->router->route($server);
        $this->assertSame('get app://self/fooitem?id=1', $request->toUriWithMethod());
        $ro = $request();
        $this->assertInstanceOf(FooItem::class, $ro);
        $this->assertInstanceOf(FooItem::class, $ro);
    }

    public function testAutoRouteSubdirectory(): void
    {
        $server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => 'http://localhost/foo-item/1/edit',
        ];
        $request = $this->router->route($server);
        $this->assertSame('get app://self/fooitem/edit?id=1', $request->toUriWithMethod());
    }

    public function testIndex(): void
    {
        $server = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => 'http://localhost/',
        ];
        $request = $this->router->route($server);
        $this->assertSame('get app://self/index', $request->toUriWithMethod());
    }
}
