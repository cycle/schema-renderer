<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\BelongsToMorphedMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class BelongsToMorphedMethodTest extends BaseTest
{
    #[\Testo\Test]
    public function testMethod(): void
    {
        $method = new BelongsToMorphedMethod('foo', 'bar');

        \Testo\Assert::same((string)$method, 'foo(BtM: bar)');
    }
}
