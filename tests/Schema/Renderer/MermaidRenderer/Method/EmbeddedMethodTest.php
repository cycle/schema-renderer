<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\EmbeddedMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class EmbeddedMethodTest extends BaseTest
{
    #[\Testo\Test]
    public function testMethod(): void
    {
        $method = new EmbeddedMethod('foo', 'bar');

        \Testo\Assert::same((string)$method, 'foo(Emb: bar)');
    }
}
