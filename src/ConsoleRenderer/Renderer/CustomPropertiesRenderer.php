<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class CustomPropertiesRenderer implements Renderer
{
    private array $exclude;

    /**
     * @param array<int, int> $exclude Values list that should be excluded
     */
    public function __construct(array $exclude)
    {
        $this->exclude = $exclude;
    }

    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        $customProperties = array_diff(array_keys($schema), $this->exclude);

        if ($customProperties === []) {
            return null;
        }

        $rows = [
            sprintf('%s:', $formatter->title('Custom props')),
        ];

        foreach ($customProperties as $property) {
            $data = $schema[$property] ?? null;

            $rows[] = sprintf(
                '    %s: %s',
                $property,
                $formatter->typecast(print_r($data, true))
            );
        }

        return \implode($formatter::LINE_SEPARATOR, $rows);
    }
}
