<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\PlainOutputRenderer\ConsoleFormatter;
use Traversable;

final class PlainOutputRenderer
{
    private const STR_RELATION = [
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
        Relation::LOAD_PROMISE => 'promise',
        Relation::LOAD_EAGER => 'eager',
    ];

    private ConsoleFormatter $formatter;

    public function __construct()
    {
        $this->formatter = new ConsoleFormatter();
    }

    public function render(SchemaInterface $schema, string ...$roles): Traversable
    {
        foreach ($roles as $role) {
            if (!$schema->defines($role)) {
                yield $this->formatter->formatColors(
                    "<fg=red>Role</> <fg=magenta>[{$role}]</> <fg=red>not defined!</>"
                );
                yield '';
                continue;
            }

            foreach ($this->renderRole($schema, $role) as $row) {
                yield $this->formatter->formatColors($row);
            }
            yield '';
        }
    }

    private function renderRole(SchemaInterface $schema, string $role): Traversable
    {
        yield from $this->renderTitle($schema, $role);
        yield from $this->renderEntity($schema, $role);
        yield from $this->renderMapper($schema, $role);
        yield from $this->renderConstrain($schema, $role);
        yield from $this->renderRepository($schema, $role);
        yield from $this->renderPrimaryKeys($schema, $role);
        yield from $this->renderFields($schema, $role);
        yield from $this->renderRelations($schema, $role);
    }

    private function renderRelations(SchemaInterface $schema, string $role): Traversable
    {
        $title = sprintf('%s:', $this->formatter->title('Relations'));
        $table = $schema->define($role, SchemaInterface::TABLE);
        $relations = $schema->define($role, SchemaInterface::RELATIONS);
        if (count($relations) === 0) {
            yield $title . ' <fg=red>no relations</>';
            return;
        }

        yield $title;

        foreach ($relations as $field => $relation) {
            $type = self::STR_RELATION[$relation[Relation::TYPE] ?? ''] ?? '?';
            $target = $relation[Relation::TARGET] ?? '?';
            $loading = self::STR_PREFETCH_MODE[$relation[Relation::LOAD] ?? ''] ?? '?';
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
            $row = "     <fg=magenta>{$role}</>-><fg=cyan>{$field}</> {$type} <fg=magenta>{$target}</> {$loading} load";

            if ($morphKey !== null) {
                $row .= sprintf('%s: %s', $this->formatter->title('Morphed key'), '<fg=green>{$morphKey}</>');
            }

            yield $row . " <fg=yellow>{$cascadeStr}</>";

            $row = "       {$nullableStr} <fg=green>{$table}</>.<fg=green>{$innerKey}</> <=";
            if ($mmEntity !== null) {
                $row .= " <fg=magenta>{$mmEntity}</>.<fg=green>{$mmInnerKey}</>";
                $row .= ' | ';
                $row .= "<fg=magenta>{$mmEntity}</>.<fg=green>{$mmOuterKey}</> ";
            }

            yield $row . "=> <fg=magenta>{$target}</>.<fg=green>{$outerKey}</>";

            if (count($where)) {
                yield sprintf(
                    '%s: %s',
                    $this->formatter->title('Where'),
                    str_replace(["\r\n", "\n"], "\n       ", "\n" . print_r($where, true))
                );
            }

            if (count($mmWhere)) {
                yield sprintf(
                    '%s: %s',
                    $this->formatter->title('Through where'),
                    str_replace(["\r\n", "\n"], "\n       ", "\n" . print_r($mmWhere, true))
                );
            }

            yield '';
        }
    }

    private function renderFields(SchemaInterface $schema, string $role): Traversable
    {
        $columns = $schema->define($role, SchemaInterface::COLUMNS);
        $title = sprintf('%s:', $this->formatter->title('Fields'));

        if (count($columns) === 0) {
            yield $title . ' <fg=red>no fields</>';
            return;
        }

        yield $title;

        yield '     (<fg=cyan>property</> -> <fg=green>db.field</> -> <fg=blue>typecast</>)';

        $types = $schema->define($role, SchemaInterface::TYPECAST);

        foreach ($columns as $property => $field) {
            $typecast = $types[$property] ?? $types[$field] ?? null;
            $row = "     <fg=cyan>{$property}</> -> <fg=green>{$field}</>";

            if ($typecast !== null) {
                $row .= sprintf(' -> <fg=blue>%s</>', implode('::', (array)$typecast));
            }

            yield $row;
        }
    }

    private function renderPrimaryKeys(SchemaInterface $schema, string $role): Traversable
    {
        $pk = $schema->define($role, SchemaInterface::PRIMARY_KEY);
        $row = sprintf('%s: ', $this->formatter->title('Primary key'));

        if ($pk === null) {
            yield $row . '<fg=red>no primary key</>';
            return;
        }

        $pk = is_array($pk) ? implode('</>, <fg=green>', $pk) : $pk;

        yield $row . "<fg=green>{$pk}</>";
    }

    private function renderRepository(SchemaInterface $schema, string $role): Traversable
    {
        $repository = $schema->define($role, SchemaInterface::REPOSITORY);
        yield sprintf(
            '%s: %s',
            $this->formatter->title('Repository'),
            $repository === null ? '<fg=red>no repository</>' : "<fg=blue>{$repository}</>"
        );
    }

    private function renderConstrain(SchemaInterface $schema, string $role): Traversable
    {
        $constrain = $schema->define($role, SchemaInterface::SCOPE);
        yield sprintf(
            '%s: %s',
            $this->formatter->title('Constrain'),
            $constrain === null ? '<fg=red>no constrain</>' : "<fg=blue>{$constrain}</>"
        );
    }

    private function renderMapper(SchemaInterface $schema, string $role): Traversable
    {
        $mapper = $schema->define($role, SchemaInterface::MAPPER);

        yield sprintf(
            '%s: %s',
            $this->formatter->title('Mapper'),
            $mapper === null ? '<fg=red>no mapper</>' : "<fg=blue>{$mapper}</>"
        );
    }

    private function renderEntity(SchemaInterface $schema, string $role): Traversable
    {
        $entity = $schema->define($role, SchemaInterface::ENTITY);

        yield sprintf(
            '%s: %s',
            $this->formatter->title('Entity'),
            $entity === null ? '<fg=red>no entity</>' : "<fg=blue>{$entity}</>"
        );
    }

    private function renderTitle(SchemaInterface $schema, string $role): Traversable
    {
        $database = $schema->define($role, SchemaInterface::DATABASE);
        $table = $schema->define($role, SchemaInterface::TABLE);

        $row = "<fg=magenta>[{$role}]</>";

        if ($database !== null) {
            $row .= " :: <fg=green>{$database}</>.<fg=green>{$table}</>";
        }

        yield $row;
    }
}
