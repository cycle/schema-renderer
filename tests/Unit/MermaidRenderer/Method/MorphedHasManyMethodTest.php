<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method\MorphedHasManyMethod;
use Testo\Assert;
use Testo\Test;

#[Test]
final class MorphedHasManyMethodTest
{
    public function testMethod(): void
    {
        $method = new MorphedHasManyMethod('foo', 'bar');

        Assert::same((string) $method, 'foo(MoHM: bar)');
    }
}
