<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class PropertyRenderer implements Renderer
{
    private int $property;
    private string $title;
    private bool $required;

    public function __construct(int $property, string $title, bool $required = false)
    {
        $this->property = $property;
        $this->title = $title;
        $this->required = $required;
    }

    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        $row = \sprintf('%s: ', $formatter->title($this->title));

        if (! isset($schema[$this->property])) {
            return $this->required ? $row . $formatter->error('not defined') : null;
        }

        $propertyValue = $schema[$this->property];

        if (\is_array($propertyValue)) {
            if (\count($propertyValue) >= 1) {
                return $row . $this->convertArrayToString($formatter, $propertyValue);
            }
            $propertyValue = '[]';
        }

        return \sprintf(
            '%s%s',
            $row,
            $formatter->typecast($propertyValue)
        );
    }

    private function convertArrayToString(Formatter $formatter, array $values): string
    {
        $string = \implode(
            "\n",
            \array_map(static fn ($property) => \sprintf(
                '  %s%s',
                $formatter->title(' '),
                $formatter->typecast($property)
            ), $values)
        );

        return \ltrim($string);
    }
}
