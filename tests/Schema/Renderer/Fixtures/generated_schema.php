<?php

declare(strict_types=1);

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;

return [
'user' => [
    SchemaInterface::DATABASE => 'default',
    SchemaInterface::TABLE => 'user',
    SchemaInterface::ENTITY => 'Cycle\\Schema\\Renderer\\Tests\\Fixtures\\User',
    SchemaInterface::MAPPER => 'Cycle\\ORM\\Mapper\\Mapper',
    SchemaInterface::REPOSITORY => null,
    SchemaInterface::SCOPE => null,
    SchemaInterface::PRIMARY_KEY => 'id',
    SchemaInterface::COLUMNS => ['id', 'email', 'balance'],
    SchemaInterface::TYPECAST => [
        'id' => 'int',
        'balance' => 'float',
    ],
    SchemaInterface::RELATIONS => [
        'tags' => [
            Relation::TYPE => Relation::MANY_TO_MANY,
            Relation::TARGET => 'tag',
            Relation::SCHEMA => [
                Relation::CASCADE => true,
                Relation::THROUGH_ENTITY => 'Cycle\\Schema\\Renderer\\Tests\\Fixtures\\TagContext',
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
],
'tag' => [
    SchemaInterface::DATABASE => 'default',
    SchemaInterface::TABLE => 'tag',
    SchemaInterface::ENTITY => 'Cycle\\Schema\\Renderer\\Tests\\Fixtures\\Tag',
    SchemaInterface::MAPPER => 'Cycle\\ORM\\Mapper\\Mapper',
    SchemaInterface::REPOSITORY => null,
    SchemaInterface::SCOPE => null,
    SchemaInterface::PRIMARY_KEY => ['id', 'name'],
    SchemaInterface::COLUMNS => ['id', 'name'],
    SchemaInterface::TYPECAST => [
        'id' => 'int',
    ],
    SchemaInterface::RELATIONS => [
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
],
'tag_context' => [
    SchemaInterface::DATABASE => 'default',
    SchemaInterface::TABLE => 'tag_user_map',
    SchemaInterface::ENTITY => 'Cycle\\Schema\\Renderer\\Tests\\Fixtures\\TagContext',
    SchemaInterface::MAPPER => 'Cycle\\ORM\\Mapper\\Mapper',
    SchemaInterface::REPOSITORY => null,
    SchemaInterface::SCOPE => null,
    SchemaInterface::PRIMARY_KEY => null,
    SchemaInterface::COLUMNS => [],
    SchemaInterface::TYPECAST => [
        'id' => 'int',
        'user_id' => 'int',
        'tag_id' => 'int',
    ],
    SchemaInterface::RELATIONS => [],
]
];
