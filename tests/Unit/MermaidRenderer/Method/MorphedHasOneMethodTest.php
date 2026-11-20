<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\MorphedHasOneMethod;
use Testo\Assert;
use Testo\Test;

#[Test]
final class MorphedHasOneMethodTest
{
    public function testMethod(): void
    {
        $method = new MorphedHasOneMethod('foo', 'bar');

        Assert::same((string) $method, 'foo(MoHO: bar)');
    }
}
