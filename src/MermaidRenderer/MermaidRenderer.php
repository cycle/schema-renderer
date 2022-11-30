<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityArrow;
use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityTable;
use Cycle\Schema\Renderer\MermaidRenderer\Schema\ClassDiagram;
use Cycle\Schema\Renderer\SchemaRenderer;
use ReflectionException;

final class MermaidRenderer implements SchemaRenderer
{
    private const METHOD_FORMAT = '%s: %s';

    public function render(array $schema): string
    {
        $class = new ClassDiagram();
        $relationMapper = new RelationMapper();

        $aliases = [];

        foreach ($schema as $key => $value) {
            $role = $key;

            if (\class_exists($role)) {
                $role = $value[SchemaInterface::ROLE];
            }

            $aliases[$key] = $role;
        }

        $aliasCollection = new AliasCollection($aliases);

        foreach ($schema as $key => $value) {
            if (!isset($value[SchemaInterface::COLUMNS])) {
                continue;
            }

            $role = $aliasCollection->getAlias($key);

            $table = new EntityTable($role);
            $arrow = new EntityArrow();

            if (\strpos($key, ':') !== false) {
                $table->addAnnotation($key);
            }

            foreach ($value[SchemaInterface::COLUMNS] as $column) {
                $typecast = $this->formatTypecast($value[SchemaInterface::TYPECAST][$column] ?? 'string');

                $table->addRow($typecast, $column);
            }

            foreach ($value[SchemaInterface::RELATIONS] ?? [] as $relationKey => $relation) {
                if (!isset($relation[Relation::TARGET], $relation[Relation::SCHEMA])) {
                    continue;
                }

                $target = $aliasCollection->getAlias($relation[Relation::TARGET]);

                if (\class_exists($target)) {
                    $target = \lcfirst($this->getClassShortName($target));
                }

                $isNullable = $relation[Relation::SCHEMA][Relation::NULLABLE] ?? false;

                $mappedRelation = $relationMapper->mapWithNode($relation[Relation::TYPE], $isNullable);

                switch ($relation[Relation::TYPE]) {
                    case Relation::MANY_TO_MANY:
                        $throughEntity = $relation[Relation::SCHEMA][Relation::THROUGH_ENTITY] ?? null;

                        if ($throughEntity) {
                            if (\class_exists($throughEntity)) {
                                $throughEntity = \lcfirst($this->getClassShortName($throughEntity));
                            }

                            $table->addMethod($relationKey, \sprintf(self::METHOD_FORMAT, $mappedRelation[0], $target));

                            // tag --* post : posts
                            $arrow->addArrow($role, $target, $relationKey, $mappedRelation[1]);
                            // postTag ..> tag : tag.posts
                            $arrow->addArrow($throughEntity, $role, "$role.$relationKey", '..>');
                            // postTag ..> post : tag.posts
                            $arrow->addArrow($throughEntity, $target, "$role.$relationKey", '..>');
                        }
                        break;
                    case Relation::EMBEDDED:
                        $methodTarget = explode('_', $target);

                        $prop = isset($methodTarget[2]) ? $methodTarget[2] . '_prop' : $target;

                        $table->addMethod(
                            $prop,
                            \sprintf(self::METHOD_FORMAT, $mappedRelation[0], $relation[Relation::TARGET])
                        );

                        $arrow->addArrow($role, $target, $prop, $mappedRelation[1]);
                        break;
                    default:
                        $table->addMethod($relationKey, \sprintf(self::METHOD_FORMAT, $mappedRelation[0], $target));
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

    /**
     * @param class-string|object $class
     * @return string
     * @throws ReflectionException
     */
    private function getClassShortName($class): string
    {
        $className = \is_object($class) ? \get_class($class) : $class;

        return \substr($className, \strrpos($className, '\\') + 1);
    }

    /**
     * @psalm-suppress MissingParamType
     */
    private function formatTypecast($typecast): string
    {
        if (\is_array($typecast)) {
            $typecast[0] = $this->getClassShortName($typecast[0]);

            return \sprintf("[%s, '%s']", "$typecast[0]::class", $typecast[1]);
        }

        if ($typecast instanceof \Closure) {
            return 'Closure';
        }

        if ($typecast === 'datetime') {
            return $typecast;
        }

        if (\class_exists($typecast)) {
            return $this->getClassShortName($typecast) . '::class';
        }

        return \htmlentities($typecast);
    }
}
