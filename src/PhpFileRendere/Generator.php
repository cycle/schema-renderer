<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRendere;

use Cycle\ORM\SchemaInterface;
use Cycle\ORM\Relation;

final class Generator
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
    private const PREFETCH_MODE = [
        Relation::LOAD_PROMISE => 'Relation::LOAD_PROMISE',
        Relation::LOAD_EAGER => 'Relation::LOAD_EAGER',
    ];
    private const GENERAL_OPTION = [
        Relation::TYPE => 'Relation::TYPE',
        Relation::TARGET => 'Relation::TARGET',
        Relation::SCHEMA => 'Relation::SCHEMA',
        Relation::LOAD => 'Relation::LOAD',
    ];

    private SchemaInterface $schema;

    public function __construct(SchemaInterface $schema)
    {
        $this->schema = $schema;
    }

    public function generate(string $role): ?string
    {
        $aliasOf = $this->schema->resolveAlias($role);

        if ($aliasOf !== null && $aliasOf !== $role) {
            // This role is an alias
            return null;
        }

        if ($this->schema->defines($role) === false) {
            // Role has no definition within the schema
            return null;
        }

        return (string)(new VarExporter($role, [
            $this->renderDatabase($role),
            $this->renderTable($role),
            $this->renderEntity($role),
            $this->renderMapper($role),
            $this->renderRepository($role),
            $this->renderScope($role),
            $this->renderPK($role),
            $this->renderFields($role),
            $this->renderTypecast($role),
            $this->renderRelations($role),
        ], true));
    }

    private function renderDatabase(string $role): VarExporter
    {
        return new VarExporter(
            'SchemaInterface::DATABASE',
            $this->schema->define($role, SchemaInterface::DATABASE)
        );
    }

    private function renderTable(string $role): VarExporter
    {
        return new VarExporter(
            'SchemaInterface::TABLE',
            $this->schema->define($role, SchemaInterface::TABLE)
        );
    }

    private function renderEntity(string $role): VarExporter
    {
        return new VarExporter(
            'SchemaInterface::ENTITY',
            $this->schema->define($role, SchemaInterface::ENTITY)
        );
    }

    private function renderMapper(string $role): VarExporter
    {
        return new VarExporter(
            'SchemaInterface::MAPPER',
            $this->schema->define($role, SchemaInterface::MAPPER)
        );
    }

    private function renderRepository(string $role): VarExporter
    {
        return new VarExporter(
            'SchemaInterface::REPOSITORY',
            $this->schema->define($role, SchemaInterface::REPOSITORY)
        );
    }

    private function renderScope(string $role): VarExporter
    {
        return new VarExporter(
            'SchemaInterface::SCOPE',
            $this->schema->define($role, SchemaInterface::SCOPE)
        );
    }

    private function renderPK(string $role): VarExporter
    {
        return new VarExporter(
            'SchemaInterface::PRIMARY_KEY',
            $this->schema->define($role, SchemaInterface::PRIMARY_KEY)
        );
    }

    private function renderFields(string $role): VarExporter
    {
        return new VarExporter(
            'SchemaInterface::COLUMNS',
            $this->schema->define($role, SchemaInterface::COLUMNS)
        );
    }

    private function renderTypecast(string $role): VarExporter
    {
        return new VarExporter(
            'SchemaInterface::TYPECAST',
            $this->schema->define($role, SchemaInterface::TYPECAST)
        );
    }

    private function renderRelations(string $role): VarExporter
    {
        $relations = $this->schema->define($role, SchemaInterface::RELATIONS) ?? [];
        $results = [];
        foreach ($relations as $field => $relation) {
            $relationResult = [];
            foreach ($relation as $option => $value) {
                $relationResult[] = $this->renderRelationOption($option, $value);
            }
            $results[] = new VarExporter($field, $relationResult, true);
        }

        return new VarExporter('SchemaInterface::RELATIONS', $results);
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
