<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\EmbeddedMethod;
use Testo\Assert;
use Testo\Test;

#[Test]
final class EmbeddedMethodTest
{
    public function testMethod(): void
    {
        $method = new EmbeddedMethod('foo', 'bar');

        Assert::same((string) $method, 'foo(Emb: bar)');
    }
}
