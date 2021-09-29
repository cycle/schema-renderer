<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

use Cycle\ORM\SchemaInterface;

final class SchemaToArrayConverter
{
    private const PROPERTIES = [
        SchemaInterface::ENTITY,
        SchemaInterface::MAPPER,
        SchemaInterface::REPOSITORY,
        SchemaInterface::DATABASE,
        SchemaInterface::TABLE,
        SchemaInterface::PRIMARY_KEY,
        SchemaInterface::COLUMNS,
        SchemaInterface::RELATIONS,
        SchemaInterface::SCOPE,
        SchemaInterface::TYPECAST,
    ];

    /**
     * @param SchemaInterface $schema
     * @param array<int, string> $customProperties
     * @return array<string, array<int, mixed>>
     */
    public function convert(SchemaInterface $schema, array $customProperties = []): array
    {
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
