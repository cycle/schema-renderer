<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityArrow;
use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityTable;
use Cycle\Schema\Renderer\SchemaRenderer;

final class MermaidRenderer implements SchemaRenderer
{
    public const DIAGRAM = <<<DIAGRAM

    erDiagram
    %s
    DIAGRAM;

    public function render(array $schema): string
    {
        $relationMapper = new RelationMapper();

        $blocks = [];

        foreach ($schema as $key => $value) {
            if (!isset($value[SchemaInterface::COLUMNS], $value[SchemaInterface::RELATIONS])) {
                continue;
            }

            $table = new EntityTable($key);
            $arrow = new EntityArrow($key);

            foreach ($value[SchemaInterface::COLUMNS] as $column) {
                $table->addRow($value[SchemaInterface::TYPECAST][$column] ?? 'string', $column);
            }

            foreach ($value[SchemaInterface::RELATIONS] as $relation) {
                if (!isset($relation[Relation::TARGET], $relation[Relation::SCHEMA])) {
                    continue;
                }

                $target = $relation[Relation::TARGET];
                $throughEntity = $relation[Relation::SCHEMA][Relation::THROUGH_ENTITY] ?? null;
                $isNullable = $relation[Relation::SCHEMA][Relation::NULLABLE] ?? false;
                $relation = $relationMapper->map($relation[Relation::TYPE]);

                if ($throughEntity) {
                    $arrow->addArrow($throughEntity, $relation, $isNullable);
                } else {
                    $arrow->addArrow($target, $relation, $isNullable);
                }
            }

            $blocks[] = $table->toString();
            $blocks[] = $arrow->toString();
        }

        return sprintf(self::DIAGRAM, implode("\n", $blocks));
    }
}
