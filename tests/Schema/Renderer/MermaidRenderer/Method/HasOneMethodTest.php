<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\HasOneMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class HasOneMethodTest extends BaseTest
{
    #[\Testo\Test]
    public function testMethod(): void
    {
        $method = new HasOneMethod('foo', 'bar');

        \Testo\Assert::same((string)$method, 'foo(HO: bar)');
    }
}
