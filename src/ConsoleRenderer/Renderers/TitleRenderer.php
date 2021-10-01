<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderers;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class TitleRenderer implements Renderer
{
    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        $database = $schema[SchemaInterface::DATABASE] ?? null;
        $table = $schema[SchemaInterface::TABLE] ?? null;

        $row = $formatter->entity("[{$role}]");

        if ($database !== null) {
            $row .= sprintf(
                " :: %s.%s",
                $formatter->column($database),
                $formatter->column($table)
            );
        }

        return $row;
    }
}
