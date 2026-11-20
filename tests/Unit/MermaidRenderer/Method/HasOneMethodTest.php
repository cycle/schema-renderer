<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\HasOneMethod;
use Testo\Assert;
use Testo\Test;

#[Test]
final class HasOneMethodTest
{
    public function testMethod(): void
    {
        $method = new HasOneMethod('foo', 'bar');

        Assert::same((string) $method, 'foo(HO: bar)');
    }
}
