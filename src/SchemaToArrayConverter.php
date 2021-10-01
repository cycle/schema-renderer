<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

use Cycle\ORM\SchemaInterface;

class SchemaToArrayConverter
{
    private const PROPERTIES = [
        SchemaInterface::ENTITY,
        SchemaInterface::MAPPER,
        SchemaInterface::SOURCE,
        SchemaInterface::REPOSITORY,
        SchemaInterface::DATABASE,
        SchemaInterface::TABLE,
        SchemaInterface::PRIMARY_KEY,
        SchemaInterface::FIND_BY_KEYS,
        SchemaInterface::COLUMNS,
        SchemaInterface::RELATIONS,
        SchemaInterface::CHILDREN,
        SchemaInterface::SCOPE,
        SchemaInterface::TYPECAST,
        SchemaInterface::SCHEMA,
    ];

    /**
     * @param SchemaInterface $schema
     * @param array<int, string> $customProperties
     * @return array<string, array<int, mixed>>
     */
    public function convert(SchemaInterface $schema, array $customProperties = []): array
    {
        // For CycleORM >= 2.x
        if (method_exists($schema, 'toArray')) {
            return $schema->toArray();
        }

        $result = [];

        $properties = [...self::PROPERTIES, ...$customProperties];

        foreach ($schema->getRoles() as $role) {
            $aliasOf = $schema->resolveAlias($role);

            if ($aliasOf !== null && $aliasOf !== $role) {
                // This role is an alias
                continue;
            }

            foreach ($properties as $property) {
                $result[$role][$property] = $schema->define($role, $property);
            }
        }

        return $result;
    }
}
