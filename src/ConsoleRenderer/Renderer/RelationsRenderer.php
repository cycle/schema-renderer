<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;

class RelationsRenderer implements Renderer
{
    private const STR_RELATION = [
        Relation::EMBEDDED => 'has embedded',
        Relation::HAS_ONE => 'has one',
        Relation::HAS_MANY => 'has many',
        Relation::BELONGS_TO => 'belongs to',
        Relation::REFERS_TO => 'refers to',
        Relation::MANY_TO_MANY => 'many to many',
        Relation::BELONGS_TO_MORPHED => 'belongs to morphed',
        Relation::MORPHED_HAS_ONE => 'morphed has one',
        Relation::MORPHED_HAS_MANY => 'morphed has many',
    ];

    private const STR_PREFETCH_MODE = [
        Relation::LOAD_PROMISE => 'lazy',
        Relation::LOAD_EAGER => 'eager',
    ];

    public function render(Formatter $formatter, array $schema, string $role): ?string
    {
        $title = \sprintf('%s:', $formatter->title('Relations'));
        $relations = $schema[SchemaInterface::RELATIONS] ?? [];

        if (\count($relations) === 0) {
            return $title . ' ' . $formatter->error('not defined');
        }

        $rows = [$title];

        foreach ($relations as $field => $relation) {
            $type = self::STR_RELATION[$relation[Relation::TYPE] ?? ''] ?? '?';
            $target = $relation[Relation::TARGET] ?? '?';
            $loading = self::STR_PREFETCH_MODE[$relation[Relation::LOAD] ?? ''] ?? 'default';
            $relSchema = $relation[Relation::SCHEMA];
            $innerKey = $relSchema[Relation::INNER_KEY] ?? '?';
            $outerKey = $relSchema[Relation::OUTER_KEY] ?? '?';
            $where = $relSchema[Relation::WHERE] ?? [];
            $cascade = $relSchema[Relation::CASCADE] ?? null;
            $cascadeStr = $cascade ? 'cascaded' : 'not cascaded';
            $nullable = $relSchema[Relation::NULLABLE] ?? null;
            $nullableStr = $nullable ? 'nullable' : ($nullable === false ? 'not null' : 'n/a');
            $morphKey = $relSchema[Relation::MORPH_KEY] ?? null;

            // Many-To-Many relation(s) options
            $mmInnerKey = $relSchema[Relation::THROUGH_INNER_KEY] ?? '?';
            $mmOuterKey = $relSchema[Relation::THROUGH_OUTER_KEY] ?? '?';
            $mmEntity = $relSchema[Relation::THROUGH_ENTITY] ?? null;
            $mmWhere = $relSchema[Relation::THROUGH_WHERE] ?? [];

            // print
            $row = \sprintf(
                '     %s->%s %s %s',
                $formatter->entity($role),
                $formatter->property($field),
                $type,
                $formatter->entity($target)
            );

            if ($morphKey !== null) {
                $row .= \sprintf(
                    ', %s: %s',
                    $formatter->title('morphed key'),
                    $this->renderKeys($formatter, $morphKey)
                );
            }

            $rows[] = $row . \sprintf(', %s loading, %s', $formatter->info($loading), $formatter->info($cascadeStr));

            $row = \sprintf(
                '       %s %s.%s <=',
                $nullableStr,
                $formatter->entity($role),
                $this->renderKeys($formatter, $innerKey)
            );

            if ($mmEntity !== null) {
                $row .= \sprintf(
                    ' %s.%s | %s.%s ',
                    $formatter->entity($mmEntity),
                    $this->renderKeys($formatter, $mmInnerKey),
                    $formatter->entity($mmEntity),
                    $this->renderKeys($formatter, $mmOuterKey)
                );
            }

            // todo: composite $outerKey
            $rows[] = $row . \sprintf(
                '=> %s.%s',
                $formatter->entity($target),
                $this->renderKeys($formatter, $outerKey)
            );

            if (count($where)) {
                $rows[] = \sprintf(
                    '%s: %s',
                    $formatter->title('Where'),
                    \str_replace(
                        ["\r\n", "\n"],
                        $formatter::LINE_SEPARATOR . '       ',
                        $formatter::LINE_SEPARATOR . print_r($where, true)
                    )
                );
            }

            if (count($mmWhere)) {
                $rows[] = \sprintf(
                    '%s: %s',
                    $formatter->title('Through where'),
                    \str_replace(
                        ["\r\n", "\n"],
                        $formatter::LINE_SEPARATOR . '       ',
                        $formatter::LINE_SEPARATOR . print_r($mmWhere, true)
                    )
                );
            }
        }

        return \implode($formatter::LINE_SEPARATOR, $rows);
    }

    /**
     * @param  array<string>|string  $keys
     */
    private function renderKeys(Formatter $formatter, $keys): string
    {
        $keys = (array)$keys;
        $braces = \count($keys) > 1;
        $keys = \array_map(
            static fn (string $key) => $formatter->property($key),
            $keys
        );

        return \sprintf(
            '%s%s%s',
            $braces ? '[' : '',
            \implode(', ', $keys),
            $braces ? ']' : ''
        );
    }
}
