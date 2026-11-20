<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\HasManyMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class HasManyMethodTest extends BaseTest
{
    #[\Testo\Test]
    public function testMethod(): void
    {
        $method = new HasManyMethod('foo', 'bar');

        \Testo\Assert::same((string)$method, 'foo(HM: bar)');
    }
}
