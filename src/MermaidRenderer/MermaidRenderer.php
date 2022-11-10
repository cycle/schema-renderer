<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityArrow;
use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityTable;
use Cycle\Schema\Renderer\MermaidRenderer\Exception\RelationNotFoundException;
use Cycle\Schema\Renderer\MermaidRenderer\Schema\ClassDiagram;
use Cycle\Schema\Renderer\SchemaRenderer;

final class MermaidRenderer implements SchemaRenderer
{
    private const METHOD_FORMAT = '%s: %s';

    /**
     * @throws RelationNotFoundException
     */
    public function render(array $schema): string
    {
        $class = new ClassDiagram();
        $relationMapper = new RelationMapper();

        foreach ($schema as $key => $value) {
            if (!isset($value[SchemaInterface::COLUMNS]) || str_contains($key, ':')) {
                continue;
            }

            $role = $value[SchemaInterface::ROLE] ?? $key;

            // to avoid mermaid class error (user:credentials)
            if (str_contains($role, ':')) {
                $role = $key;
            }

            $table = new EntityTable($role);
            $arrow = new EntityArrow();

            foreach ($value[SchemaInterface::COLUMNS] as $column) {
                $table->addRow($value[SchemaInterface::TYPECAST][$column] ?? 'string', $column);
            }

            foreach ($value[SchemaInterface::RELATIONS] ?? [] as $relationKey => $relation) {
                if (!isset($relation[Relation::TARGET], $relation[Relation::SCHEMA])) {
                    continue;
                }

                $target = $relation[Relation::TARGET];
                $isNullable = $relation[Relation::SCHEMA][Relation::NULLABLE] ?? false;

                $mappedRelation = $relationMapper->mapWithNode($relation[Relation::TYPE], $isNullable);

                switch ($relation[Relation::TYPE]) {
                    case Relation::MANY_TO_MANY:
                        $throughEntity = $relation[Relation::SCHEMA][Relation::THROUGH_ENTITY] ?? null;

                        if ($throughEntity) {
                            $table->addMethod($relationKey, sprintf(self::METHOD_FORMAT, $mappedRelation[0], $target));

                            // tag --* post : posts
                            $arrow->addArrow($role, $target, $relationKey, $mappedRelation[1]);
                            // postTag ..> tag : tag.posts
                            $arrow->addArrow($throughEntity, $role, "$role.$relationKey", '..>');
                            // postTag ..> post : tag.posts
                            $arrow->addArrow($throughEntity, $target, "$role.$relationKey", '..>');
                        }
                        break;
                    case Relation::EMBEDDED:
                        // explode string like user:credentials
                        $methodTarget = explode(':', $target);

                        $table->addMethod(
                            $methodTarget[1] ?? $target,
                            sprintf(self::METHOD_FORMAT, $mappedRelation[0], $relationKey)
                        );

                        $arrowTarget = str_replace(':', '&#58', $target);

                        $arrow->addArrow($role, $relationKey, $arrowTarget, $mappedRelation[1]);
                        break;
                    default:
                        $table->addMethod($relationKey, sprintf(self::METHOD_FORMAT, $mappedRelation[0], $target));
                        $arrow->addArrow($role, $target, $relationKey, $mappedRelation[1]);
                }
            }

            foreach ($value[SchemaInterface::CHILDREN] ?? [] as $children) {
                $arrow->addArrow($role, $children, 'STI', '--|>');
            }

            if (isset($value[SchemaInterface::PARENT])) {
                $arrow->addArrow($value[SchemaInterface::PARENT], $role, 'JTI', '--|>');
            }

            $class->addEntity($table);
            $class->addEntity($arrow);
        }

        return (string)$class;
    }
}
