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

        $row = $formatter->entity("[{$role}]");

        if ($database !== null) {
            $row .= sprintf(
                ' :: %s.%s',
                $formatter->column($database),
                $formatter->column($table)
            );
        }

        return $row;
    }
}
