<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\BelongsToMorphedMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class BelongsToMorphedMethodTest extends BaseTest
{
    public function testMethod(): void
    {
        $method = new BelongsToMorphedMethod('foo', 'bar');

        $this->assertSame('foo(BtM: bar)', (string)$method);
    }
}
