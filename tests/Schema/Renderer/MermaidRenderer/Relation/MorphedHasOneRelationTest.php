<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\MorphedHasOneRelation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class MorphedHasOneRelationTest extends BaseTest
{
    public function testRelation(): void
    {
        $rel = new MorphedHasOneRelation(
            'foo',
            'bar',
            'Foo comment',
            false
        );

        $this->assertSame('foo --> bar : Foo comment', (string)$rel);
    }
}
