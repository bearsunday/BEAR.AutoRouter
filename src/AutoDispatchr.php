<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Annotation\DefaultSchemeHost;
use BEAR\Sunday\DispatcherInterface;
use BEAR\Sunday\ResourceInvocation;
use Ray\Di\InjectorInterface;

use function assert;
use function class_exists;
use function parse_url;
use function sprintf;
use function strtolower;
use function substr;

use const PHP_URL_PATH;

final class AutoDispatchr implements DispatcherInterface
{
    public function __construct(
        private InjectorInterface $injector,
        private AbstractAppMeta $appMeta,
        #[DefaultSchemeHost] private string $schemeHost
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function route(array $server): ResourceInvocation
    {
        $method = strtolower('on' . $server['REQUEST_METHOD']);
        $schema = substr($this->schemeHost, 0, 3) === 'app' ? 'App' : 'Page';
        $namespace = sprintf('%s\\Resource\\%s', $this->appMeta->name, $schema);
        $directory = sprintf('%s/Resource/%s/', $this->appMeta->appDir, $schema);
        $uri = (string) parse_url($server['REQUEST_URI'], PHP_URL_PATH);
        if (substr($uri, -1, 1) === '/') {
            $uri .= 'index';
        }

        $autoRoute = new AutoRoute($namespace, $directory, method: $method);
        $router = $autoRoute->getRouter();
        $route = $router->route('', $uri);
        assert(class_exists($route->class));
        $ro = $this->injector->getInstance($route->class);
        assert($ro instanceof ResourceObject);

        return new ResourceInvocation($ro, $route->method, $route->class, $route->arguments);
    }
}
