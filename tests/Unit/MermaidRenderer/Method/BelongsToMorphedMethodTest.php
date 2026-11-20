<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\BelongsToMorphedMethod;
use Testo\Assert;
use Testo\Test;

#[Test]
final class BelongsToMorphedMethodTest
{
    public function testMethod(): void
    {
        $method = new BelongsToMorphedMethod('foo', 'bar');

        Assert::same((string) $method, 'foo(BtM: bar)');
    }
}
