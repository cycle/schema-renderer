<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class MacrosRenderer implements Renderer
{
    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        if (! \defined(SchemaInterface::class.'::MACROS')) {
            return null;
        }
        $macrosList = (array)($schema[SchemaInterface::MACROS] ?? []);

        if ($macrosList === []) {
            return null;
        }

        $rows = [
            sprintf('%s:', $formatter->title('Entity macros')),
        ];

        foreach ($macrosList as $definition) {
            $data = (array)$definition;

            // macros class
            $class = \is_string($data[0] ?? null)
                ? $formatter->typecast($data[0])
                : $formatter->error('undefined');
            $rows[] = sprintf('  %s', $class);

            // macros params
            $params = $data[1] ?? null;
            if ($params === null) {
                continue;
            }
            if (is_array($params)) {
                foreach ($params as $key => $value) {
                    $row = '    ';
                    if (\is_string($key)) {
                        $row .= $formatter->property($key).' : ';
                    }
                    $row .= $formatter->info($this->printValue($formatter, $value));
                    $rows[] = $row;
                }
            } else {
                $rows[] = $formatter->typecast($this->printValue($formatter, $data));
            }
        }

        return \implode($formatter::LINE_SEPARATOR, $rows);
    }

    /**
     * @param  mixed  $value
     */
    private function printValue(Formatter $formatter, $value): string
    {
        return str_replace(
            ["\r\n", "\n"],
            $formatter::LINE_SEPARATOR.'       ',
            print_r($value, true)
        );
    }
}
