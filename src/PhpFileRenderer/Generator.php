<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer;

interface Generator
{
    /**
     * @param array $schema
     * @param string $role
     * @return array<int, VarExporter>
     */
    public function generate(array $schema, string $role): array;
}
