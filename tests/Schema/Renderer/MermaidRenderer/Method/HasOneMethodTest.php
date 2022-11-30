<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\HasOneMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class HasOneMethodTest extends BaseTest
{
    public function testMethod(): void
    {
        $method = new HasOneMethod('foo', 'bar');

        $this->assertSame('foo(HO: bar)', (string)$method);
    }
}
