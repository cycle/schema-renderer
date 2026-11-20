<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\BelongsToMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class BelongsToMethodTest extends BaseTest
{
    #[\Testo\Test]
    public function testMethod(): void
    {
        $method = new BelongsToMethod('foo', 'bar');

        \Testo\Assert::same((string)$method, 'foo(BT: bar)');
    }
}
