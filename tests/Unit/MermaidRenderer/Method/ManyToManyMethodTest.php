<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\ManyToManyMethod;
use Testo\Assert;
use Testo\Test;

#[Test]
final class ManyToManyMethodTest
{
    public function testMethod(): void
    {
        $method = new ManyToManyMethod('foo', 'bar');

        Assert::same((string) $method, 'foo(MtM: bar)');
    }
}
