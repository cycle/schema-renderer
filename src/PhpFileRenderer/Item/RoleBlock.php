<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Item;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayBlock;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayItem;

class RoleBlock extends ArrayBlock
{
    /**
     * @param int|string $key
     * @param mixed $value
     */
    protected function wrapItem($key, $value): ArrayItem
    {
        $item = parent::wrapItem($key, $value);
        if ($key === SchemaInterface::RELATIONS && is_array($value)) {
            $item->setValue(new RelationsBlock($value), false);
        }
        return $item;
    }
}
