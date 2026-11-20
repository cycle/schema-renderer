<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer;

use Cycle\Schema\Renderer\MermaidRenderer\Method;
use Testo\Assert;
use Testo\Test;

#[Test]
final class MethodTest
{
    public function testCasting(): void
    {
        $method = new Method(
            'foo',
            'bar',
            'FB',
        );

        Assert::same((string) $method, 'foo(FB: bar)');
    }
}
