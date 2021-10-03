<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Item;

use Cycle\ORM\Relation;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayBlock;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayItem;

class RelationBlock extends ArrayBlock
{
    private const RELATION_SCHEMA_KEYS = [
        Relation::MORPH_KEY => 'Relation::MORPH_KEY',
        Relation::CASCADE => 'Relation::CASCADE',
        Relation::NULLABLE => 'Relation::NULLABLE',
        Relation::OUTER_KEY => 'Relation::OUTER_KEY',
        Relation::INNER_KEY => 'Relation::INNER_KEY',
        Relation::WHERE => 'Relation::WHERE',
        Relation::THROUGH_INNER_KEY => 'Relation::THROUGH_INNER_KEY',
        Relation::THROUGH_OUTER_KEY => 'Relation::THROUGH_OUTER_KEY',
        Relation::THROUGH_ENTITY => 'Relation::THROUGH_ENTITY',
        Relation::THROUGH_WHERE => 'Relation::THROUGH_WHERE',
    ];

    /**
     * @param int|string $key
     * @param mixed $value
     */
    protected function wrapItem($key, $value): ArrayItem
    {
        $item = parent::wrapItem($key, $value);
        if ($key === Relation::SCHEMA && is_array($value)) {
            $item->setValue(new self($value, self::RELATION_SCHEMA_KEYS), false);
        }
        return $item;
    }
}
