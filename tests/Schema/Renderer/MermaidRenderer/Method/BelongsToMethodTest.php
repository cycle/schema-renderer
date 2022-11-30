<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\BelongsToMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class BelongsToMethodTest extends BaseTest
{
    public function testMethod(): void
    {
        $method = new BelongsToMethod('foo', 'bar');

        $this->assertSame('foo(BT: bar)', (string)$method);
    }
}
