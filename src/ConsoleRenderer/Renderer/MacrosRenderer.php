<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class MacrosRenderer implements Renderer
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

        $macrosList = (array)($schema[$this->property] ?? []);

        if ($macrosList === []) {
            return null;
        }

        $rows = [
            \sprintf('%s:', $formatter->title($this->title)),
        ];

        foreach ($macrosList as $definition) {
            $data = (array)$definition;

            // macros class
            $class = \is_string($data[0] ?? null)
                ? $formatter->typecast($data[0])
                : $formatter->error('undefined');
            $rows[] = \sprintf('%s%s', $formatter->title(' '), $class);

            // macros params
            $params = $data[1] ?? null;
            if ($params === null) {
                continue;
            }
            if (\is_array($params)) {
                foreach ($params as $key => $value) {
                    $row = $formatter->title(' ') . ' - ';
                    if (\is_string($key)) {
                        $row .= $formatter->property($key) . ' : ';
                    }
                    $row .= $formatter->info($this->printValue($value, $formatter));
                    $rows[] = $row;
                }
            } elseif (is_string($params)) {
                $rows[] = $formatter->title(' ') . ' - ' . $formatter->info($this->printValue($params, $formatter));
            } else {
                $rows[] = $formatter->typecast($this->printValue($data, $formatter));
            }
        }

        return \implode($formatter::LINE_SEPARATOR, $rows);
    }

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
