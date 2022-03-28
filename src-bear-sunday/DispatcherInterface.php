<?php

namespace BEAR\Sunday;

use BEAR\Resource\Request;

/**
 * @psalm-import-type Server from \BEAR\Sunday\Extension\Router\RouterInterface
 */
interface DispatcherInterface
{
    /**
     * @param Server $server
     */
    public function route(array $server): Request;
}
