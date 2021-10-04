<?php

declare(strict_types=1);

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface as Schema;

return [
    'user' => [
        Schema::ENTITY => 'Cycle\\Schema\\Renderer\\Tests\\Fixture\\User',
        Schema::MAPPER => 'Cycle\\ORM\\Mapper\\Mapper',
        Schema::DATABASE => 'default',
        Schema::TABLE => 'user',
        Schema::PRIMARY_KEY => 'id',
        Schema::COLUMNS => ['id', 'email', 'balance'],
        Schema::RELATIONS => [
            'tags' => [
                Relation::TYPE => Relation::MANY_TO_MANY,
                Relation::TARGET => 'tag',
                Relation::SCHEMA => [
                    Relation::CASCADE => true,
                    Relation::THROUGH_ENTITY => 'tag_context',
                    Relation::INNER_KEY => 'id',
                    Relation::OUTER_KEY => 'id',
                    Relation::THROUGH_INNER_KEY => 'user_id',
                    Relation::THROUGH_OUTER_KEY => 'tag_id',
                ],
            ],
            'tag' => [
                Relation::TYPE => Relation::BELONGS_TO,
                Relation::TARGET => 'tag',
                Relation::SCHEMA => [
                    Relation::CASCADE => true,
                    Relation::INNER_KEY => 'tag_id',
                    Relation::OUTER_KEY => 'id',
                ],
            ],
        ],
        Schema::TYPECAST => [
            'id' => 'int',
            'balance' => 'float',
        ],
        Schema::SCHEMA => [],
    ],
    'tag' => [
        Schema::ENTITY => 'Cycle\\Schema\\Renderer\\Tests\\Fixture\\Tag',
        Schema::MAPPER => 'Cycle\\ORM\\Mapper\\Mapper',
        Schema::DATABASE => 'default',
        Schema::TABLE => 'tag',
        Schema::PRIMARY_KEY => ['id', 'name'],
        Schema::COLUMNS => ['id', 'name'],
        Schema::RELATIONS => [
            'user' => [
                Relation::TYPE => Relation::BELONGS_TO,
                Relation::TARGET => 'user',
                Relation::SCHEMA => [
                    Relation::CASCADE => true,
                    Relation::INNER_KEY => 'user_id',
                    Relation::OUTER_KEY => 'id',
                ],
            ],
        ],
        Schema::TYPECAST => [
            'id' => 'int',
        ],
        Schema::SCHEMA => [],
    ],
    'tag_context' => [
        Schema::ENTITY => 'Cycle\\Schema\\Renderer\\Tests\\Fixture\\TagContext',
        Schema::MAPPER => 'Cycle\\ORM\\Mapper\\Mapper',
        Schema::DATABASE => 'default',
        Schema::TABLE => 'tag_user_map',
        Schema::COLUMNS => [],
        Schema::RELATIONS => [],
        Schema::TYPECAST => [
            'id' => 'int',
            'user_id' => 'int',
            'tag_id' => 'int',
        ],
        Schema::SCHEMA => [],
    ],
];
