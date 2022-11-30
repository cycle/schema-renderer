<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\ManyToManyRelation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class ManyToManyRelationTest extends BaseTest
{
    public function testRelation(): void
    {
        $rel = new ManyToManyRelation(
            'foo',
            'bar',
            'Foo comment',
            false
        );

        $this->assertSame('foo --* bar : Foo comment', (string)$rel);
    }
}
