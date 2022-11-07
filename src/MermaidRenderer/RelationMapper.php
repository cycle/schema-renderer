<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

use Cycle\ORM\Relation;

final class RelationMapper
{
    const RELATIONS = [
        Relation::HAS_ONE => 'has_one',
        Relation::HAS_MANY => 'has_many',
        Relation::BELONGS_TO => 'belongs_to',
        Relation::MANY_TO_MANY => 'many_to_many',
        Relation::REFERS_TO => 'refers_to',
        Relation::MORPHED_HAS_MANY => 'morphed_has_many',
        Relation::MORPHED_HAS_ONE => 'morphed_has_one',
        Relation::BELONGS_TO_MORPHED => 'belongs_to_morphed',
    ];

    public function map(int $code): string
    {
        return self::RELATIONS[$code] ?? '???';
    }
}
