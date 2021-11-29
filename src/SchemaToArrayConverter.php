<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

use Cycle\ORM\SchemaInterface;

/**
 * The class converts the {@see SchemaInterface} class implementation into a Cycle ORM specific array
 */
final class SchemaToArrayConverter
{
    /** @var array<string, int> */
    private array $constants;

    public function __construct(?ConstantsInterface $constants = null)
    {
        $this->constants = ($constants ?? new SchemaConstants())->all();
    }

    /**
     * @param SchemaInterface $schema
     * @param array<int, int> $customProperties
     *
     * @return array<string, array<int, mixed>>
     */
    public function convert(SchemaInterface $schema, array $customProperties = []): array
    {
        // For CycleORM >= 2.x
        if (method_exists($schema, 'toArray')) {
            return $schema->toArray();
        }

        $result = [];

        $properties = array_merge($this->constants, $customProperties);

        foreach ($schema->getRoles() as $role) {
            foreach ($properties as $property) {
                /** @psalm-suppress ReservedWord */
                $value = $schema->define($role, $property);
                if ($value === null) {
                    continue;
                }
                $result[$role][$property] = $value;
            }
        }

        return $result;
    }
}
