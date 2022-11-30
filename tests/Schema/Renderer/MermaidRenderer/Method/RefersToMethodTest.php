<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\RefersToMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class RefersToMethodTest extends BaseTest
{
    public function testMethod(): void
    {
        $method = new RefersToMethod('foo', 'bar');

        $this->assertSame('foo(RT: bar)', (string)$method);
    }
}
