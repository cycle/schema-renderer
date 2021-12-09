<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class TitleRenderer implements Renderer
{
    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        $database = $schema[SchemaInterface::DATABASE] ?? '<undefined databse>';
        $table = $schema[SchemaInterface::TABLE] ?? '<undefined table>';

        return \sprintf(
            '%s :: %s.%s',
            $formatter->entity("[{$role}]"),
            $formatter->column($database),
            $formatter->column($table)
        );
    }
}
