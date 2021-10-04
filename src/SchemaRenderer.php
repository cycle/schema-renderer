<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

use Cycle\ORM\SchemaInterface;

interface SchemaRenderer
{
    /**
     * @param array<string, array<int, mixed>> $schema Cycle ORM schema as array. You can convert {@see SchemaInterface}
     * instance via {@see SchemaToArrayConverter}
     */
    public function render(array $schema): string;
}
