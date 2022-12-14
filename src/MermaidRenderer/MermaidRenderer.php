<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

use Cycle\ORM\Relation as SchemaRelation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityTable;
use Cycle\Schema\Renderer\MermaidRenderer\Method\BelongsToMethod;
use Cycle\Schema\Renderer\MermaidRenderer\Method\BelongsToMorphedMethod;
use Cycle\Schema\Renderer\MermaidRenderer\Method\EmbeddedMethod;
use Cycle\Schema\Renderer\MermaidRenderer\Method\HasManyMethod;
use Cycle\Schema\Renderer\MermaidRenderer\Method\HasOneMethod;
use Cycle\Schema\Renderer\MermaidRenderer\Method\ManyToManyMethod;
use Cycle\Schema\Renderer\MermaidRenderer\Method\MorphedHasManyMethod;
use Cycle\Schema\Renderer\MermaidRenderer\Method\MorphedHasOneMethod;
use Cycle\Schema\Renderer\MermaidRenderer\Method\RefersToMethod;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\BelongsToMorphedRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\BelongsToRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\EmbeddedRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\HasManyRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\HasOneRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\JoinedInheritanceRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\ManyToManyRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\MorphedHasManyRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\MorphedHasOneRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\RefersToRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Relation\SingleInheritanceRelation;
use Cycle\Schema\Renderer\MermaidRenderer\Schema\ClassDiagram;
use Cycle\Schema\Renderer\SchemaConstants;
use Cycle\Schema\Renderer\SchemaRenderer;

final class MermaidRenderer implements SchemaRenderer
{
    use StringFormatter;

    public function render(array $schema): string
    {
        $class = new ClassDiagram();
        $roleAliasCollection = new RoleAliasCollection($schema);

        $constants = (new SchemaConstants())->all();

        foreach ($schema as $key => $value) {
            if (!isset($value[SchemaInterface::COLUMNS])) {
                continue;
            }

            $role = $roleAliasCollection->getAlias($key);

            $entityTable = new EntityTable($role);
            $entityRelation = new EntityRelation();

            if (\strpos($key, ':') !== false) {
                $entityTable->addAnnotation(
                    new Annotation($key)
                );
            }

            foreach ($value[SchemaInterface::COLUMNS] as $column) {
                $entityTable->addColumn(
                    new Column($value[SchemaInterface::TYPECAST][$column] ?? 'string', $column)
                );
            }

            foreach ($value[SchemaInterface::RELATIONS] ?? [] as $relationKey => $relation) {
                if (!isset($relation[SchemaRelation::TARGET], $relation[SchemaRelation::SCHEMA])) {
                    continue;
                }

                $target = $roleAliasCollection->getAlias($relation[SchemaRelation::TARGET]);

                if (\class_exists($target)) {
                    $target = \lcfirst($this->getClassShortName($target));
                }

                $isNullable = $relation[SchemaRelation::SCHEMA][SchemaRelation::NULLABLE] ?? false;

                switch ($relation[SchemaRelation::TYPE]) {
                    case SchemaRelation::HAS_ONE:
                        $entityTable->addMethod(
                            new HasOneMethod($relationKey, $target)
                        );
                        $entityRelation->addRelation(
                            new HasOneRelation($role, $target, $relationKey, $isNullable)
                        );
                        break;
                    case SchemaRelation::HAS_MANY:
                        $entityTable->addMethod(
                            new HasManyMethod($relationKey, $target)
                        );
                        $entityRelation->addRelation(
                            new HasManyRelation($role, $target, $relationKey, $isNullable)
                        );
                        break;
                    case SchemaRelation::BELONGS_TO:
                        $entityTable->addMethod(
                            new BelongsToMethod($relationKey, $target)
                        );
                        $entityRelation->addRelation(
                            new BelongsToRelation($role, $target, $relationKey, $isNullable)
                        );
                        break;
                    case SchemaRelation::MANY_TO_MANY:
                        $throughEntity = $relation[SchemaRelation::SCHEMA][SchemaRelation::THROUGH_ENTITY] ?? null;

                        if ($throughEntity !== null) {
                            if (\class_exists($throughEntity)) {
                                $throughEntity = \lcfirst($this->getClassShortName($throughEntity));
                            }

                            $entityTable->addMethod(
                                new ManyToManyMethod($relationKey, $target)
                            );
                            $entityRelation->addRelation(
                                new ManyToManyRelation($role, $target, $relationKey, $isNullable)
                            );
                            $entityRelation->addRelation(
                                new Relation($throughEntity, $role, "$role.$relationKey", '..>', $isNullable)
                            );
                            $entityRelation->addRelation(
                                new Relation($throughEntity, $target, "$role.$relationKey", '..>', $isNullable)
                            );
                        }
                        break;
                    case SchemaRelation::REFERS_TO:
                        $entityTable->addMethod(
                            new RefersToMethod($relationKey, $target)
                        );
                        $entityRelation->addRelation(
                            new RefersToRelation($role, $target, $relationKey, $isNullable)
                        );
                        break;
                    case SchemaRelation::MORPHED_HAS_MANY:
                        $entityTable->addMethod(
                            new MorphedHasManyMethod($relationKey, $target)
                        );
                        $entityRelation->addRelation(
                            new MorphedHasManyRelation($role, $target, $relationKey, $isNullable)
                        );
                        break;
                    case SchemaRelation::MORPHED_HAS_ONE:
                        $entityTable->addMethod(
                            new MorphedHasOneMethod($relationKey, $target)
                        );
                        $entityRelation->addRelation(
                            new MorphedHasOneRelation($role, $target, $relationKey, $isNullable)
                        );
                        break;
                    case SchemaRelation::BELONGS_TO_MORPHED:
                        $entityTable->addMethod(
                            new BelongsToMorphedMethod($relationKey, $target)
                        );
                        $entityRelation->addRelation(
                            new BelongsToMorphedRelation($role, $target, $relationKey, $isNullable)
                        );
                        break;
                    case SchemaRelation::EMBEDDED:
                        $methodTarget = \explode('_', $target);
                        $prop = isset($methodTarget[2]) ? $methodTarget[2] . '_prop' : $target;

                        $entityTable->addMethod(
                            new EmbeddedMethod($prop, $relation[SchemaRelation::TARGET])
                        );
                        $entityRelation->addRelation(
                            new EmbeddedRelation($role, $target, $prop, $isNullable)
                        );
                        break;
                    default:
                        throw new \ErrorException(\sprintf('Relation `%s` not found.', $relation[SchemaRelation::TYPE]));
                }
            }

            foreach ($value[SchemaInterface::CHILDREN] ?? [] as $children) {
                $entityRelation->addRelation(
                    new SingleInheritanceRelation($role, $children, false)
                );
            }

            if (isset($constants['PARENT'], $value[$constants['PARENT']])) {
                $entityRelation->addRelation(
                    new JoinedInheritanceRelation($value[$constants['PARENT']], $role, false)
                );
            }

            $class->addEntity($entityTable);
            $class->addEntity($entityRelation);
        }

        return (string)$class;
    }
}
