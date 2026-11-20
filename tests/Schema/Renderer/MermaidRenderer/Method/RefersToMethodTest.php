<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\RefersToMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class RefersToMethodTest extends BaseTest
{
    #[\Testo\Test]
    public function testMethod(): void
    {
        $method = new RefersToMethod('foo', 'bar');

        \Testo\Assert::same((string)$method, 'foo(RT: bar)');
    }
}
