<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class CustomPropertiesRenderer implements Renderer
{
    private array $exclude;

    /**
     * @param  array<int, int>  $exclude  Values list that should be excluded
     */
    public function __construct(array $exclude)
    {
        $this->exclude = $exclude;
    }

    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        $customProperties = \array_diff(\array_keys($schema), $this->exclude);

        if ($customProperties === []) {
            return null;
        }

        $rows = [
            \sprintf('%s:', $formatter->title('Custom props')),
        ];

        foreach ($customProperties as $property) {
            $data = $schema[$property] ?? null;

            $rows[] = \sprintf(
                '%s%s: %s',
                $formatter->title(' '),
                $property,
                $formatter->typecast($this->printValue($data, $formatter))
            );
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
