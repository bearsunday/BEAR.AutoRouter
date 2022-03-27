<?php

namespace BEAR\Sunday;

/**
 * @psalm-import-type Server from \BEAR\Sunday\Extension\Router\RouterInterface
 */
interface DispatcherInterface
{
    /**
     * @param Server $server
     */
    public function route(array $server): ResourceInvocation;
}
