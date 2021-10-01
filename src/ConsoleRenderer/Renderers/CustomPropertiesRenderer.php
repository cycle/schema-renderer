<?php

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderers;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;
use ReflectionClass;

class CustomPropertiesRenderer implements Renderer
{
    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        $refl = new ReflectionClass(SchemaInterface::class);

        $customProperties = array_diff(array_keys($schema), $refl->getConstants());

        if ($customProperties === []) {
            return null;
        }

        $rows = [
            sprintf('%s:', $formatter->title('Custom props'))
        ];

        foreach ($customProperties as $property) {
            $data = $schema[$property] ?? null;

            $rows[] = sprintf(
                '    %s: %s',
                $property,
                $formatter->typecast($data)
            );
        }

        return implode("\n", $rows);
    }
}
