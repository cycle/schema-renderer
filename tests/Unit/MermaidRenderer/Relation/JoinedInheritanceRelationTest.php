<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\JoinedInheritanceRelation;
use Testo\Assert;
use Testo\Test;

#[Test]
final class JoinedInheritanceRelationTest
{
    public function testRelation(): void
    {
        $rel = new JoinedInheritanceRelation(
            'foo',
            'bar',
            false,
        );

        Assert::same((string) $rel, 'foo --|> bar : JTI');
    }
}
