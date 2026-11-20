<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\MorphedHasManyRelation;
use Testo\Assert;
use Testo\Test;

#[Test]
final class MorphedHasManyRelationTest
{
    public function testRelation(): void
    {
        $rel = new MorphedHasManyRelation(
            'foo',
            'bar',
            'Foo comment',
            false,
        );

        Assert::same((string) $rel, 'foo --o bar : Foo comment');
    }
}
