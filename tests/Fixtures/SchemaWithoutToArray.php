<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Fixtures;

use Cycle\ORM\SchemaInterface;

/**
 * A schema without the `toArray()` method of Cycle ORM 2.x, as Cycle ORM 1.x schemas are.
 */
final class SchemaWithoutToArray implements SchemaInterface
{
    public function __construct(
        private readonly SchemaInterface $schema,
    ) {}

    public function getRoles(): array
    {
        return $this->schema->getRoles();
    }

    public function getRelations(string $role): array
    {
        return $this->schema->getRelations($role);
    }

    public function defines(string $role): bool
    {
        return $this->schema->defines($role);
    }

    public function define(string $role, int $property): mixed
    {
        return $this->schema->define($role, $property);
    }

    public function defineRelation(string $role, string $relation): array
    {
        return $this->schema->defineRelation($role, $relation);
    }

    public function resolveAlias(string $role): ?string
    {
        return $this->schema->resolveAlias($role);
    }

    public function getInheritedRoles(string $parent): array
    {
        return $this->schema->getInheritedRoles($parent);
    }
}
