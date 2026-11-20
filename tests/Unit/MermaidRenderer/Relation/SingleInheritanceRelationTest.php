<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\SingleInheritanceRelation;
use Testo\Assert;
use Testo\Test;

#[Test]
final class SingleInheritanceRelationTest
{
    public function testRelation(): void
    {
        $rel = new SingleInheritanceRelation(
            'foo',
            'bar',
            false,
        );

        Assert::same((string) $rel, 'foo --|> bar : STI');
    }
}
