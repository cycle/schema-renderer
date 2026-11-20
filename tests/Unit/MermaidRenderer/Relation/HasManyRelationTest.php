<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\HasManyRelation;
use Testo\Assert;
use Testo\Test;

#[Test]
final class HasManyRelationTest
{
    public function testRelation(): void
    {
        $rel = new HasManyRelation(
            'foo',
            'bar',
            'Foo comment',
            false,
        );

        Assert::same((string) $rel, 'foo --o bar : Foo comment');
    }
}
