<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\ORM\Relation;
use Cycle\Schema\Renderer\MermaidRenderer\Exception\RelationNotFoundException;
use Cycle\Schema\Renderer\MermaidRenderer\RelationMapper;
use Cycle\Schema\Renderer\Tests\BaseTest;

class RelationMapperTest extends BaseTest
{
    public function testMapRelation()
    {
        $mapper = new RelationMapper();

        $this->assertSame(['belongs_to', '||--||'], $mapper->mapWithNode(Relation::BELONGS_TO));
    }

    public function testMapNonExistsRelation(): void
    {
        $this->expectException(RelationNotFoundException::class);

        $mapper = new RelationMapper();

        $mapper->mapWithNode(123);
    }
}
