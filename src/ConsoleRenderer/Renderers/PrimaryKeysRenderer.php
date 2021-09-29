<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderers;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class PrimaryKeysRenderer implements Renderer
{
    public function render(Formatter $formatter, array $schema, string $role): string
    {
        $pk = $schema[SchemaInterface::PRIMARY_KEY] ?? null;

        $row = sprintf('%s: ', $formatter->title('Primary key'));

        if ($pk === null) {
            return $row . $formatter->error('not defined');
        }

        $pk = array_map(static function (string $key) use ($formatter) {
            return $formatter->column($key);
        }, (array)$pk);

        return $row . implode(', ', $pk);
    }
}
