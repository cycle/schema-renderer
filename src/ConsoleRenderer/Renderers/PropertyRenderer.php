<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderers;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class PropertyRenderer implements Renderer
{
    /** @var int|string */
    private $property;
    private string $title;

    public function __construct($property, string $title)
    {
        $this->property = $property;
        $this->title = $title;
    }

    public function render(Formatter $formatter, array $schema, string $role): string
    {
        $data = $schema[$this->property] ?? null;

        return sprintf(
            '%s: %s',
            $formatter->title($this->title),
            $data === null ? $formatter->error('not defined') : $formatter->typecast($data)
        );
    }
}
