<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\EmbeddedMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class EmbeddedMethodTest extends BaseTest
{
    public function testMethod(): void
    {
        $method = new EmbeddedMethod('foo', 'bar');

        $this->assertSame('foo(Emb: bar)', (string)$method);
    }
}
