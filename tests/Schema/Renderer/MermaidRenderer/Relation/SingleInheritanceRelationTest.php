<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\SingleInheritanceRelation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class SingleInheritanceRelationTest extends BaseTest
{
    #[\Testo\Test]
    public function testRelation(): void
    {
        $rel = new SingleInheritanceRelation(
            'foo',
            'bar',
            false
        );

        \Testo\Assert::same((string)$rel, 'foo --|> bar : STI');
    }
}
