<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\Resource\InvokerInterface;
use BEAR\Resource\Request;
use BEAR\Resource\ResourceObject;
use BEAR\Resource\Uri;
use BEAR\Sunday\Annotation\DefaultSchemeHost;
use BEAR\Sunday\DispatcherInterface;
use Ray\Di\InjectorInterface;

use function assert;
use function class_exists;
use function parse_url;
use function sprintf;
use function str_ends_with;
use function str_replace;
use function strlen;
use function strtolower;
use function substr;

use const PHP_URL_PATH;

final class AutoDispatchr implements DispatcherInterface
{
    public function __construct(
        private InjectorInterface $injector,
        private AbstractAppMeta $appMeta,
        #[DefaultSchemeHost] private string $schemeHost,
        private InvokerInterface $invoker
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function route(array $server): Request
    {
        $method = strtolower('on' . $server['REQUEST_METHOD']);
        $schema = substr($this->schemeHost, 0, 3) === 'app' ? 'App' : 'Page';
        $namespace = sprintf('%s\\Resource\\%s', $this->appMeta->name, $schema);
        $directory = sprintf('%s/Resource/%s/', $this->appMeta->appDir, $schema);
        $uri = (string) parse_url($server['REQUEST_URI'], PHP_URL_PATH);
        if (str_ends_with($uri, '/')) {
            $uri .= 'index';
        }

        $autoRoute = new AutoRoute($namespace, $directory, method: $method);
        $router = $autoRoute->getRouter();
        $route = $router->route('', $uri);
        assert(class_exists($route->class));
        $matchUri = str_replace('\\', '/', substr($route->class, strlen($namespace)));

        $ro = $this->injector->getInstance($route->class);
        $ro->uri = new Uri($this->schemeHost . strtolower($matchUri)); // @phpstan-ignore-line
        assert($ro instanceof ResourceObject);

        /** @psalm-suppress MixedArgumentTypeCoercion */
        return new Request($this->invoker, $ro, strtolower($server['REQUEST_METHOD']), $route->arguments);
    }
}
