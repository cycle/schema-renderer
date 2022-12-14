<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\EmbeddedRelation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class EmbeddedRelationTest extends BaseTest
{
    public function testRelation(): void
    {
        $rel = new EmbeddedRelation(
            'foo',
            'bar',
            'Foo comment',
            false
        );

        $this->assertSame('foo -- bar : Foo comment', (string)$rel);
    }
}
