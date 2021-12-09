<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class ColumnsRenderer implements Renderer
{
    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        $rows = [];

        $columns = $schema[SchemaInterface::COLUMNS] ?? [];
        $title = \sprintf('%s:', $formatter->title('Fields'));

        if (! \is_array($columns) || \count($columns) === 0) {
            return $title . ' ' . $formatter->error('not defined');
        }

        $padding = $formatter->title('') . '  ';

        $rows[] = $title;

        $rows[] = \sprintf(
            '%s(%s -> %s -> %s)',
            $padding,
            $formatter->property('property'),
            $formatter->column('db.field'),
            $formatter->typecast('typecast')
        );

        $types = $schema[SchemaInterface::TYPECAST] ?? [];

        foreach ($columns as $property => $field) {
            $typecast = $types[$property] ?? $types[$field] ?? null;
            $row = \sprintf(
                '%s%s -> %s',
                $padding,
                $formatter->property((string)$property),
                $formatter->column($field)
            );

            if ($typecast !== null && $typecast !== [] && $typecast !== '') {
                $row .= \sprintf(
                    ' -> %s',
                    $formatter->typecast(\implode('::', (array)$typecast))
                );
            }

            $rows[] = $row;
        }

        return \implode($formatter::LINE_SEPARATOR, $rows);
    }
}
