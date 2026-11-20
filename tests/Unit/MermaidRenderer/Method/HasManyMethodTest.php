<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\HasManyMethod;
use Testo\Assert;
use Testo\Test;

#[Test]
final class HasManyMethodTest
{
    public function testMethod(): void
    {
        $method = new HasManyMethod('foo', 'bar');

        Assert::same((string) $method, 'foo(HM: bar)');
    }
}
