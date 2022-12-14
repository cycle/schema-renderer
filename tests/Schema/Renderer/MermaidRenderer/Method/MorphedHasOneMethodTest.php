<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\MorphedHasOneMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class MorphedHasOneMethodTest extends BaseTest
{
    public function testMethod(): void
    {
        $method = new MorphedHasOneMethod('foo', 'bar');

        $this->assertSame('foo(MoHO: bar)', (string)$method);
    }
}
