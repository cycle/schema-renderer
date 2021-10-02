<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Item;

use Cycle\ORM\Relation;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayBlock;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayItem;

class RelationsBlock extends ArrayBlock
{
    private const OPTION_KEYS = [
        Relation::TYPE => 'Relation::TYPE',
        Relation::TARGET => 'Relation::TARGET',
        Relation::SCHEMA => 'Relation::SCHEMA',
        Relation::LOAD => 'Relation::LOAD',
    ];

    private const OPTION_VALUES = [
        Relation::LOAD => [
            Relation::LOAD_PROMISE => 'Relation::LOAD_PROMISE',
            Relation::LOAD_EAGER => 'Relation::LOAD_EAGER',
        ],
        Relation::TYPE => [
            Relation::HAS_ONE => 'Relation::HAS_ONE',
            Relation::HAS_MANY => 'Relation::HAS_MANY',
            Relation::BELONGS_TO => 'Relation::BELONGS_TO',
            Relation::REFERS_TO => 'Relation::REFERS_TO',
            Relation::MANY_TO_MANY => 'Relation::MANY_TO_MANY',
            Relation::BELONGS_TO_MORPHED => 'Relation::BELONGS_TO_MORPHED',
            Relation::MORPHED_HAS_ONE => 'Relation::MORPHED_HAS_ONE',
            Relation::MORPHED_HAS_MANY => 'Relation::MORPHED_HAS_MANY',
        ],
    ];

    /**
     * @param int|string $key
     * @param mixed $value
     */
    protected function wrapItem($key, $value): ArrayItem
    {
        $item = parent::wrapItem($key, $value);
        if (is_array($value)) {
            $item->setValue(new RelationBlock($value, self::OPTION_KEYS, self::OPTION_VALUES), false);
        }
        return $item;
    }
}
