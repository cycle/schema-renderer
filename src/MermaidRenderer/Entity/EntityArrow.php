<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Entity;

final class EntityArrow implements EntityInterface
{
    private const PARENT_NAME = 0;
    private const CHILDREN_NAME = 1;
    private const RELATION_NAME = 2;
    private const NODE = 3;

    private const BLOCK = '%s %s %s : %s';

    private array $arrows = [];

    public function addArrow(string $parent, string $children, string $relation, string $node): void
    {
        $this->arrows[] = [$parent, $children, $relation, $node];
    }

    public function toString(): string
    {
        $arrows = [];

        foreach ($this->arrows as $arrow) {
            $arrows[] = sprintf(
                self::BLOCK,
                $arrow[self::PARENT_NAME],
                $arrow[self::NODE],
                $arrow[self::CHILDREN_NAME],
                $arrow[self::RELATION_NAME]
            );
        }

        return implode("\n", $arrows);
    }
}
