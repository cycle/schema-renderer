<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation\HasOneRelation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class HasOneRelationTest extends BaseTest
{
    #[\Testo\Test]
    public function testRelation(): void
    {
        $rel = new HasOneRelation(
            'foo',
            'bar',
            'Foo comment',
            false
        );

        \Testo\Assert::same((string)$rel, 'foo --> bar : Foo comment');
    }
}
