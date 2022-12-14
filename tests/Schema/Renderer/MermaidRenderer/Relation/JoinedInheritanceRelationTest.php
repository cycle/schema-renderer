<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\JoinedInheritanceRelation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class JoinedInheritanceRelationTest extends BaseTest
{
    public function testRelation(): void
    {
        $rel = new JoinedInheritanceRelation(
            'foo',
            'bar',
            false
        );

        $this->assertSame('foo --|> bar : JTI', (string)$rel);
    }
}
