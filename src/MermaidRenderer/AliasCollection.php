<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

final class AliasCollection
{
    private array $aliases;

    public function __construct(array $aliases)
    {
        foreach ($aliases as $key => $value) {
            if (\preg_match('/[^_a-zA-Z0-9]/u', $value)) {
                $role = \preg_replace('/[^a-z0-9_]/u', '_', $value);
                $oldRole = $role;
                $counter = 0;

                while (isset($aliases[$role])) {
                    $counter++;
                    $role = $oldRole . '_' . $counter;
                }

                $aliases[$key] = $role;
            }
        }

        $this->aliases = $aliases;
    }

    public function getAlias(string $key): string
    {
        return $this->aliases[$key] ?? 'undefined';
    }
}
