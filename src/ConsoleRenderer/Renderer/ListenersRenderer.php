<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class ListenersRenderer implements Renderer
{
    private int $property;
    private string $title;

    public function __construct(int $property, string $title)
    {
        $this->property = $property;
        $this->title = $title;
    }

    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        if (! isset($schema[$this->property])) {
            return null;
        }

        $listeners = (array)($schema[$this->property] ?? []);

        if ($listeners === []) {
            return null;
        }

        $rows = [
            \sprintf('%s:', $formatter->title($this->title)),
        ];

        foreach ($listeners as $definition) {
            $data = (array)$definition;

            // Listener class
            $class = \is_string($data[0] ?? null)
                ? $formatter->typecast($data[0])
                : $formatter->error('undefined');
            $rows[] = \sprintf('%s%s', $formatter->title(' '), $class);

            // Listener arguments
            $args = $data[1] ?? null;
            if ($args === null) {
                continue;
            }
            if (\is_array($args)) {
                foreach ($args as $key => $value) {
                    $row = $formatter->title(' ') . ' - ';
                    if (\is_string($key)) {
                        $row .= $formatter->property($key) . ' : ';
                    }
                    $row .= $formatter->info($this->printValue($value, $formatter));
                    $rows[] = $row;
                }
            } elseif (is_string($args)) {
                $rows[] = $formatter->title(' ') . ' - ' . $formatter->info($this->printValue($args, $formatter));
            } else {
                $rows[] = $formatter->typecast($this->printValue($data, $formatter));
            }
        }

        return \implode($formatter::LINE_SEPARATOR, $rows);
    }

    /**
     * @param mixed $value
     */
    private function printValue($value, Formatter $formatter): string
    {
        $data = \trim(\var_export($value, true), '\'');
        $data = \array_map(
            static fn (string $row): string => $formatter->title(' ') . $row,
            \explode("\n", $data)
        );

        return \ltrim(
            \implode("\n", $data)
        );
    }
}
