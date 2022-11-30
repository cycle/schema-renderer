<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\ManyToManyMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class ManyToManyMethodTest extends BaseTest
{
    public function testMethod(): void
    {
        $method = new ManyToManyMethod('foo', 'bar');

        $this->assertSame('foo(MtM: bar)', (string)$method);
    }
}
