<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Generators;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\PhpFileRenderer\Generator;
use Cycle\Schema\Renderer\PhpFileRenderer\VarExporter;

class RelationsGenerator implements Generator
{
    private const RELATION = [
        Relation::HAS_ONE => 'Relation::HAS_ONE',
        Relation::HAS_MANY => 'Relation::HAS_MANY',
        Relation::BELONGS_TO => 'Relation::BELONGS_TO',
        Relation::REFERS_TO => 'Relation::REFERS_TO',
        Relation::MANY_TO_MANY => 'Relation::MANY_TO_MANY',
        Relation::BELONGS_TO_MORPHED => 'Relation::BELONGS_TO_MORPHED',
        Relation::MORPHED_HAS_ONE => 'Relation::MORPHED_HAS_ONE',
        Relation::MORPHED_HAS_MANY => 'Relation::MORPHED_HAS_MANY',
    ];

    private const PREFETCH_MODE = [
        Relation::LOAD_PROMISE => 'Relation::LOAD_PROMISE',
        Relation::LOAD_EAGER => 'Relation::LOAD_EAGER',
    ];

    private const RELATION_OPTION = [
        Relation::MORPH_KEY => 'Relation::MORPH_KEY',
        Relation::CASCADE => 'Relation::CASCADE',
        Relation::NULLABLE => 'Relation::NULLABLE',
        Relation::OUTER_KEY => 'Relation::OUTER_KEY',
        Relation::INNER_KEY => 'Relation::INNER_KEY',
        Relation::WHERE => 'Relation::WHERE',
        Relation::THROUGH_INNER_KEY => 'Relation::THROUGH_INNER_KEY',
        Relation::THROUGH_OUTER_KEY => 'Relation::THROUGH_OUTER_KEY',
        Relation::THROUGH_ENTITY => 'Relation::THROUGH_ENTITY',
        Relation::THROUGH_WHERE => 'Relation::THROUGH_WHERE',
    ];

    private const GENERAL_OPTION = [
        Relation::TYPE => 'Relation::TYPE',
        Relation::TARGET => 'Relation::TARGET',
        Relation::SCHEMA => 'Relation::SCHEMA',
        Relation::LOAD => 'Relation::LOAD',
    ];

    public function generate(array $schema, string $role): array
    {
        $relations = $schema[SchemaInterface::RELATIONS] ?? [];

        $results = [];
        foreach ($relations as $field => $relation) {
            $relationResult = [];
            foreach ($relation as $option => $value) {
                $relationResult[] = $this->renderRelationOption($option, $value);
            }

            $results[] = new VarExporter($field, $relationResult, true);
        }

        return [
            new VarExporter('SchemaInterface::RELATIONS', $results)
        ];
    }

    private function renderRelationOption(int $option, $value): VarExporter
    {
        $item = new VarExporter(self::GENERAL_OPTION[$option] ?? (string)$option, $value);

        // replace numeric keys and values with constants
        if ($option === Relation::LOAD && array_key_exists($value, self::PREFETCH_MODE)) {
            $item->setValue(self::PREFETCH_MODE[$value], false);
        } elseif ($option === Relation::TYPE && array_key_exists($value, self::RELATION)) {
            $item->setValue(self::RELATION[$value], false);
        } elseif ($option === Relation::SCHEMA && is_array($value)) {
            $item->setValue($this->renderRelationSchemaKeys($value));
        }

        return $item;
    }

    private function renderRelationSchemaKeys(array $value): array
    {
        $result = [];
        foreach ($value as $listKey => $listValue) {
            $result[] = new VarExporter(
                array_key_exists($listKey, self::RELATION_OPTION) ? self::RELATION_OPTION[$listKey] : (string)$listKey,
                $listValue
            );
        }

        return $result;
    }
}
