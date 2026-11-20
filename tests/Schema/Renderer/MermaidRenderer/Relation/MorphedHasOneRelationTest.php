<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\MorphedHasOneRelation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class MorphedHasOneRelationTest extends BaseTest
{
    #[\Testo\Test]
    public function testRelation(): void
    {
        $rel = new MorphedHasOneRelation(
            'foo',
            'bar',
            'Foo comment',
            false
        );

        \Testo\Assert::same((string)$rel, 'foo --> bar : Foo comment');
    }
}
