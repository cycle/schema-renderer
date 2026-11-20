<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Entity;

use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation;
use Testo\Assert;
use Testo\Test;

#[Test]
final class EntityRelationTest
{
    public function testToString(): void
    {
        $entityRelation = new EntityRelation();

        $entityRelation->addRelation(
            new Relation('user', 'tasks', 'Foo comment', '-->', false),
        );
        $entityRelation->addRelation(
            new Relation('user', 'posts', 'Foo comment', '--o', true),
        );

        Assert::same((string) $entityRelation, <<<BLOCK
        user --> tasks : Foo comment
        user --o "nullable" posts : Foo comment
        BLOCK);
    }
}
