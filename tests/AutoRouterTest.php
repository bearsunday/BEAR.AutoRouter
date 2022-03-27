<?php

declare(strict_types=1);

namespace BEAR\AutoRouter;

use PHPUnit\Framework\TestCase;

class AutoRouterTest extends TestCase
{
    /** @var AutoRouter */
    protected $autoRouter;

    protected function setUp(): void
    {
        $this->autoRouter = new AutoRouter();
    }

    public function testIsInstanceOfAutoRouter(): void
    {
        $actual = $this->autoRouter;
        $this->assertInstanceOf(AutoRouter::class, $actual);
    }
}
