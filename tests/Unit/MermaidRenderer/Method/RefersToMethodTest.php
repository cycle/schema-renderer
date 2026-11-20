<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\RefersToMethod;
use Testo\Assert;
use Testo\Test;

#[Test]
final class RefersToMethodTest
{
    public function testMethod(): void
    {
        $method = new RefersToMethod('foo', 'bar');

        Assert::same((string) $method, 'foo(RT: bar)');
    }
}
