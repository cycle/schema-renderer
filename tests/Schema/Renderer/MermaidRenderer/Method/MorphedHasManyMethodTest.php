<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\MorphedHasManyMethod;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class MorphedHasManyMethodTest extends BaseTest
{
    public function testMethod(): void
    {
        $method = new MorphedHasManyMethod('foo', 'bar');

        $this->assertSame('foo(MoHM: bar)', (string)$method);
    }
}
