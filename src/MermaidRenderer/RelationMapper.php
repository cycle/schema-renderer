<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

use Cycle\ORM\Relation;
use Cycle\Schema\Renderer\MermaidRenderer\Exception\RelationNotFoundException;

final class RelationMapper
{
    private const RELATION_TYPE = 1;

    private const RELATIONS = [
        Relation::HAS_ONE => ['HO', '-->'],
        Relation::HAS_MANY => ['HM', '--o'],
        Relation::BELONGS_TO => ['BT', '-->'],
        Relation::MANY_TO_MANY => ['MtM', '--*'],
        Relation::REFERS_TO => ['RT', '-->'],
        Relation::MORPHED_HAS_MANY => ['MoHM', '--o'],
        Relation::MORPHED_HAS_ONE => ['MoHO', '-->'],
        Relation::BELONGS_TO_MORPHED => ['BtM', '-->'],
        Relation::EMBEDDED => ['Emb', '--'],
    ];

    /**
     * Map relation name
     *
     * @param int $code
     * @param bool $isNullable
     *
     * @throws RelationNotFoundException
     *
     * @return array
     */
    public function mapWithNode(int $code, bool $isNullable = false): array
    {
        if (!isset(self::RELATIONS[$code])) {
            throw new RelationNotFoundException();
        }

        $relation = self::RELATIONS[$code];

        if ($isNullable) {
            $relation[self::RELATION_TYPE] = $relation[self::RELATION_TYPE] . ' "nullable"';
        }

        return $relation;
    }
}
