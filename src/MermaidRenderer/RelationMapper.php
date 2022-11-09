<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

use Cycle\ORM\Relation;
use Cycle\Schema\Renderer\MermaidRenderer\Exception\RelationNotFoundException;

final class RelationMapper
{
    private const RELATION_TYPE = 1;

    private const RELATIONS = [
        Relation::HAS_ONE => ['has_one', '||--||'],
        Relation::HAS_MANY => ['has_many', '||--|{'],
        Relation::BELONGS_TO => ['belongs_to', '||--||'],
        Relation::MANY_TO_MANY => ['many_to_many', '||--|{'],
        Relation::REFERS_TO => ['refers_to', '||--||'],
        Relation::MORPHED_HAS_MANY => ['morphed_has_many', '||--|{'],
        Relation::MORPHED_HAS_ONE => ['morphed_has_one', '||--||'],
        Relation::BELONGS_TO_MORPHED => ['belongs_to_morphed', '||--||'],
    ];

    /**
     * Map relation with node
     *
     * @param int $code
     * @param bool $isNullable
     * @return string[]
     * @throws RelationNotFoundException
     */
    public function mapWithNode(int $code, bool $isNullable = false): array
    {
        if (!isset(self::RELATIONS[$code])) {
            throw new RelationNotFoundException();
        }

        $relation = self::RELATIONS[$code];

        if ($isNullable) {
            $relation[self::RELATION_TYPE] = substr_replace($relation[self::RELATION_TYPE], 'o', -2, 1);
        }

        return $relation;
    }
}
