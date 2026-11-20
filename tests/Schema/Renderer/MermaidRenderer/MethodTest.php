<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\Schema\Renderer\MermaidRenderer\Method;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class MethodTest extends BaseTest
{
    #[\Testo\Test]
    public function testCasting(): void
    {
        $method = new Method(
            'foo',
            'bar',
            'FB'
        );

        \Testo\Assert::same((string)$method, 'foo(FB: bar)');
    }
}
