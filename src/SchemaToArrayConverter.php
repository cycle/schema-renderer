<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

use Cycle\ORM\SchemaInterface;

final class SchemaToArrayConverter
{
    /**
     * @param SchemaInterface $schema
     * @param array<int, int> $customProperties
     * @return array<string, array<int, mixed>>
     */
    public function convert(SchemaInterface $schema, array $customProperties = []): array
    {
        // For CycleORM >= 2.x
        if (method_exists($schema, 'toArray')) {
            return $schema->toArray();
        }

        $result = [];

        $properties = [
            ...$this->getSchemaConstants(),
            ...$customProperties,
        ];

        foreach ($schema->getRoles() as $role) {
            foreach ($properties as $property) {
                $result[$role][$property] = $schema->define($role, $property);
            }
        }

        return $result;
    }

    /**
     * @return array<int, int>
     */
    private function getSchemaConstants(): array
    {
        $result = array_filter(
            (new \ReflectionClass(SchemaInterface::class))->getConstants(),
            static fn ($value): bool => is_int($value)
        );
        return array_values($result);
    }
}
