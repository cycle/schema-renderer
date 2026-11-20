<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\BelongsToMethod;
use Testo\Assert;
use Testo\Test;

#[Test]
final class BelongsToMethodTest
{
    public function testMethod(): void
    {
        $method = new BelongsToMethod('foo', 'bar');

        Assert::same((string) $method, 'foo(BT: bar)');
    }
}
