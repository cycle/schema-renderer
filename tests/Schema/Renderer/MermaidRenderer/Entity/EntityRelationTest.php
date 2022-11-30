<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Entity;

use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation;
use Cycle\Schema\Renderer\Tests\BaseTest;

class EntityRelationTest extends BaseTest
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

        $this->assertSame(<<<BLOCK
        user --> tasks : Foo comment
        user --o "nullable" posts : Foo comment
        BLOCK, (string)$entityRelation);
    }
}
