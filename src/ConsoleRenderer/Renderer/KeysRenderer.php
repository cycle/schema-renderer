<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class KeysRenderer implements Renderer
{
    private string $title;
    private int $key;
    private bool $required;

    public function __construct(
        int $key = SchemaInterface::PRIMARY_KEY,
        string $title = 'Primary key',
        bool $required = true
    ) {
        $this->title = $title;
        $this->key = $key;
        $this->required = $required;
    }

    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        $keys = $schema[$this->key] ?? null;

        $row = \sprintf('%s: ', $formatter->title($this->title));

        if ($keys === null || $keys === '' || $keys === []) {
            return $this->required ? $row . $formatter->error('not defined') : null;
        }

        $keys = \array_map(
            static fn (string $key) => $formatter->property($key),
            (array)$keys
        );

        return $row . \implode(', ', $keys);
    }
}
