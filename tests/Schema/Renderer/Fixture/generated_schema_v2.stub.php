<?php

declare(strict_types=1);

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface as Schema;

return [
    'user' => [
        Schema::MAPPER => Cycle\ORM\Mapper\Mapper::class,
        Schema::DATABASE => 'default',
        Schema::TABLE => 'user',
        Schema::PRIMARY_KEY => 'id',
        Schema::COLUMNS => ['id', 'email', 'balance'],
        Schema::TYPECAST => [
            'id' => 'int',
            'balance' => 'float',
        ],
        Schema::SCHEMA => [],
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
        Schema::ENTITY => Cycle\Schema\Renderer\Tests\Fixture\User::class,
    ],
    'tag' => [
        Schema::MAPPER => Cycle\ORM\Mapper\Mapper::class,
        Schema::DATABASE => 'default',
        Schema::TABLE => 'tag',
        Schema::PRIMARY_KEY => ['id', 'name'],
        Schema::COLUMNS => ['id', 'name'],
        Schema::TYPECAST => [
            'id' => 'int',
        ],
        Schema::SCHEMA => [],
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
        Schema::ENTITY => Cycle\Schema\Renderer\Tests\Fixture\Tag::class,
    ],
    'tag_context' => [
        Schema::MAPPER => Cycle\ORM\Mapper\Mapper::class,
        Schema::DATABASE => 'default',
        Schema::TABLE => 'tag_user_map',
        Schema::COLUMNS => [],
        Schema::TYPECAST => [
            'id' => 'int',
            'user_id' => 'int',
            'tag_id' => 'int',
        ],
        Schema::SCHEMA => [],
        Schema::RELATIONS => [],
        Schema::ENTITY => Cycle\Schema\Renderer\Tests\Fixture\TagContext::class,
    ],
];
