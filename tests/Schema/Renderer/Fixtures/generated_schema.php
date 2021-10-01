<?php

declare(strict_types=1);

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;

return [
'user' => [
    ROLE => null,
    ENTITY => null,
    MAPPER => null,
    SOURCE => null,
    REPOSITORY => null,
    DATABASE => null,
    TABLE => null,
    PRIMARY_KEY => null,
    FIND_BY_KEYS => null,
    COLUMNS => null,
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
    CHILDREN => null,
    SCOPE => null,
    TYPECAST => null,
    SCHEMA => null,
],
'tag' => [
    ROLE => null,
    ENTITY => null,
    MAPPER => null,
    SOURCE => null,
    REPOSITORY => null,
    DATABASE => null,
    TABLE => null,
    PRIMARY_KEY => null,
    FIND_BY_KEYS => null,
    COLUMNS => null,
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
    CHILDREN => null,
    SCOPE => null,
    TYPECAST => null,
    SCHEMA => null,
],
'tag_context' => [
    ROLE => null,
    ENTITY => null,
    MAPPER => null,
    SOURCE => null,
    REPOSITORY => null,
    DATABASE => null,
    TABLE => null,
    PRIMARY_KEY => null,
    FIND_BY_KEYS => null,
    COLUMNS => null,
    SchemaInterface::RELATIONS => [],
    CHILDREN => null,
    SCOPE => null,
    TYPECAST => null,
    SCHEMA => null,
]
];