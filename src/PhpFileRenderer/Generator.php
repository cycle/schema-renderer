<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer;

interface Generator
{
    public function generate(array $schema, string $role): VarExporter;
}
