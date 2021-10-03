<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class PropertyRenderer implements Renderer
{
    /** @var int|string */
    private $property;
    private string $title;
    private bool $required;

    public function __construct($property, string $title, bool $required = false)
    {
        $this->property = $property;
        $this->title = $title;
        $this->required = $required;
    }

    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        $row = sprintf('%s: ', $formatter->title($this->title));

        if (!array_key_exists($this->property, $schema)) {
            return $this->required ? $row . $formatter->error('not defined') : null;
        }

        return sprintf(
            '%s%s',
            $row,
            $formatter->typecast($schema[$this->property])
        );
    }
}
