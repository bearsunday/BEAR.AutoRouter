<?php

declare(strict_types=1);

namespace BEAR\Sunday;

use BEAR\Resource\ResourceObject;

final class ResourceInvocation
{
    public function __construct(
        private ResourceObject $ro,
        public string $method,
        public string $class,
        public array $arguments
    ){}

    public function __invoke(): ResourceObject
    {
        return call_user_func([$this->ro, $this->method], ...$this->arguments);
    }
}
