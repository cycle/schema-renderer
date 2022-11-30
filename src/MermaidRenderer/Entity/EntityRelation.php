<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Entity;

use Cycle\Schema\Renderer\MermaidRenderer\Relation;

final class EntityRelation implements EntityInterface
{
    /**
     * @var Relation[]
     */
    private array $relations = [];

    public function addRelation(Relation $relation): void
    {
        $this->relations[] = $relation;
    }

    public function __toString(): string
    {
        $relations = \array_map(function (Relation $relation) {
            return (string) $relation;
        }, $this->relations);

        return \implode("\n", $relations);
    }
}
