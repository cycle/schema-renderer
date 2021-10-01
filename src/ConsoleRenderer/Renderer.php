<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer;

interface Renderer
{
    public function render(Formatter $formatter, array $schema, string $role): ?string;
}
