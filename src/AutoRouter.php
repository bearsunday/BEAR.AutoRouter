<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\Sunday\Annotation\DefaultSchemeHost;
use BEAR\Sunday\Extension\Router\RouterInterface;
use BEAR\Sunday\Extension\Router\RouterMatch;

use function parse_url;
use function sprintf;
use function str_replace;
use function strlen;
use function strtolower;
use function substr;

use const PHP_URL_PATH;

final class AutoRouter implements RouterInterface
{
    public function __construct(
        private AbstractAppMeta $appMeta,
        #[DefaultSchemeHost] private string $schemeHost // page://self
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function match(array $globals, array $server)
    {
        $method = strtolower('on' . $server['REQUEST_METHOD']);
        $schema = substr($this->schemeHost, 0, 3) === 'app' ? 'App' : 'Page';
        $namespace = sprintf('%s\\Resource\\%s', $this->appMeta->name, $schema);
        $directory = sprintf('%s/Resource/%s/', $this->appMeta->appDir, $schema);
        $uri = (string) parse_url($server['REQUEST_URI'], PHP_URL_PATH);
        $autoRoute = new AutoRoute($namespace, $directory, method: $method);
        $router = $autoRoute->getRouter();
        $route = $router->route('', $uri);

        // convert
        $matchUri = str_replace('\\', '/', substr($route->class, strlen($namespace)));
        $arguments = $route->arguments;

        /** @var array<string, mixed> $arguments */
        return new RouterMatch(
            strtolower($server['REQUEST_METHOD']),
            $this->schemeHost . $matchUri,
            $arguments
        );
    }

    /**
     * {inheritDoc}
     */
    public function generate($name, $data)
    {
        return false;
    }
}
