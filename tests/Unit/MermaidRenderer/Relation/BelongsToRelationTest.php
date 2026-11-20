<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\BelongsToRelation;
use Testo\Assert;
use Testo\Test;

#[Test]
final class BelongsToRelationTest
{
    public function testRelation(): void
    {
        $rel = new BelongsToRelation(
            'foo',
            'bar',
            'Foo comment',
            false,
        );

        Assert::same((string) $rel, 'foo --> bar : Foo comment');
    }
}
