<?php

declare(strict_types=1);

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;

return [
'user' => [
    SchemaInterface::ROLE => null,
    SchemaInterface::ENTITY => 'Cycle\\Schema\\Renderer\\Tests\\Fixtures\\User',
    SchemaInterface::MAPPER => 'Cycle\\ORM\\Mapper\\Mapper',
    SchemaInterface::SOURCE => null,
    SchemaInterface::REPOSITORY => null,
    SchemaInterface::DATABASE => 'default',
    SchemaInterface::TABLE => 'user',
    SchemaInterface::PRIMARY_KEY => 'id',
    SchemaInterface::FIND_BY_KEYS => null,
    SchemaInterface::COLUMNS => ['id', 'email', 'balance'],
    SchemaInterface::RELATIONS => [
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
    SchemaInterface::CHILDREN => null,
    SchemaInterface::SCOPE => null,
    SchemaInterface::TYPECAST => [
        'id' => 'int',
        'balance' => 'float',
    ],
    SchemaInterface::SCHEMA => [],
],
'tag' => [
    SchemaInterface::ROLE => null,
    SchemaInterface::ENTITY => 'Cycle\\Schema\\Renderer\\Tests\\Fixtures\\Tag',
    SchemaInterface::MAPPER => 'Cycle\\ORM\\Mapper\\Mapper',
    SchemaInterface::SOURCE => null,
    SchemaInterface::REPOSITORY => null,
    SchemaInterface::DATABASE => 'default',
    SchemaInterface::TABLE => 'tag',
    SchemaInterface::PRIMARY_KEY => ['id', 'name'],
    SchemaInterface::FIND_BY_KEYS => null,
    SchemaInterface::COLUMNS => ['id', 'name'],
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
    SchemaInterface::CHILDREN => null,
    SchemaInterface::SCOPE => null,
    SchemaInterface::TYPECAST => [
        'id' => 'int',
    ],
    SchemaInterface::SCHEMA => [],
],
'tag_context' => [
    SchemaInterface::ROLE => null,
    SchemaInterface::ENTITY => 'Cycle\\Schema\\Renderer\\Tests\\Fixtures\\TagContext',
    SchemaInterface::MAPPER => 'Cycle\\ORM\\Mapper\\Mapper',
    SchemaInterface::SOURCE => null,
    SchemaInterface::REPOSITORY => null,
    SchemaInterface::DATABASE => 'default',
    SchemaInterface::TABLE => 'tag_user_map',
    SchemaInterface::PRIMARY_KEY => null,
    SchemaInterface::FIND_BY_KEYS => null,
    SchemaInterface::COLUMNS => [],
    SchemaInterface::RELATIONS => [],
    SchemaInterface::CHILDREN => null,
    SchemaInterface::SCOPE => null,
    SchemaInterface::TYPECAST => [
        'id' => 'int',
        'user_id' => 'int',
        'tag_id' => 'int',
    ],
    SchemaInterface::SCHEMA => [],
]
];