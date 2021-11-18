<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Item;

use Cycle\ORM\Relation;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayBlock;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayItem;

class RelationBlock extends ArrayBlock
{
    /**
     * @see SchemaInterface
     */
    private const RELATION_SCHEMA_KEYS = [
        'MORPH_KEY',
        'CASCADE',
        'NULLABLE',
        'OUTER_KEY',
        'INNER_KEY',
        'INVERSION',
        'WHERE',
        'ORDER_BY',
        'THROUGH_INNER_KEY',
        'THROUGH_OUTER_KEY',
        'THROUGH_ENTITY',
        'THROUGH_WHERE',
        // Cycle ORM v1 deprecations:
        'THOUGH_INNER_KEY',
        'THOUGH_OUTER_KEY',
        'THOUGH_ENTITY',
        'THOUGH_WHERE',
    ];

    /**
     * @param int|string $key
     * @param mixed $value
     */
    protected function wrapItem($key, $value): ArrayItem
    {
        $item = parent::wrapItem($key, $value);
        if ($key === Relation::SCHEMA && is_array($value)) {
            $item->setValue(new self($value, $this->getReplaceKeys()), false);
        }
        return $item;
    }

    /**
     * @return array<int, string>
     */
    private function getReplaceKeys(): array
    {
        static $result = [];
        if ($result !== []) {
            return $result;
        }
        $constants = (new \ReflectionClass(Relation::class))->getConstants();
        foreach ($constants as $name => $value) {
            if (!in_array($name, self::RELATION_SCHEMA_KEYS, true) || array_key_exists($value, $result)) {
                continue;
            }
            $result[$value] = 'Relation::' . $name;
        }

        return $result;
    }
}
