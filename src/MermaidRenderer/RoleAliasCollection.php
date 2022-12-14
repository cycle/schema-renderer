<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

use Cycle\ORM\SchemaInterface;

final class RoleAliasCollection
{
    private array $aliases = [];

    /**
     * @param array<string, array<int, mixed>> $schema
     */
    public function __construct(array $schema)
    {
        foreach ($schema as $key => $value) {
            $role = $key;

            if (\class_exists($role)) {
                $role = $value[SchemaInterface::ROLE] ?? 'undefinedRole';
            }

            $this->aliases[$key] = $role;
        }

        foreach ($this->aliases as $key => $role) {
            if (\preg_match('/[^_a-zA-Z0-9]/u', $role)) {
                $role = $this->makeAlias($role);
                $this->aliases[$key] = $role;
            }
        }
    }

    public function getAlias(string $role): string
    {
        return $this->aliases[$role] ?? 'undefined';
    }

    /**
     * @param non-empty-string $role
     *
     * @return string
     */
    private function makeAlias(string $role): string
    {
        $role = \preg_replace('/[^a-z0-9_]/u', '_', $role);
        $oldRole = $role;

        $counter = 0;
        while (isset($this->aliases[$role])) {
            $counter++;
            $role = $oldRole . '_' . $counter;
        }

        return $role;
    }
}
