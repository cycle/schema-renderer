<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Schema;

use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityInterface;
use Cycle\Schema\Renderer\MermaidRenderer\Stringable;

final class ClassDiagram implements SchemaInterface, Stringable
{
    private const DIAGRAM = <<<DIAGRAM

    classDiagram
    %s

    DIAGRAM;

    private array $entities = [];

    public function __toString(): string
    {
        return sprintf(self::DIAGRAM, \implode("\n", $this->entities));
    }

    public function addEntity(EntityInterface $entity): void
    {
        $this->entities[] = $entity;
    }
}
