<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\HasManyRelation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class HasManyRelationTest extends BaseTest
{
    #[\Testo\Test]
    public function testRelation(): void
    {
        $rel = new HasManyRelation(
            'foo',
            'bar',
            'Foo comment',
            false
        );

        \Testo\Assert::same((string)$rel, 'foo --o bar : Foo comment');
    }
}
