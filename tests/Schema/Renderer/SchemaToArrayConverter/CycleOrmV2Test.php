<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\SchemaToArrayConverter;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Relation;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\BaseTest;
use Cycle\Schema\Renderer\Tests\Fixture\Tag;
use Cycle\Schema\Renderer\Tests\Fixture\TagContext;
use Cycle\Schema\Renderer\Tests\Fixture\User;

class CycleOrmV2Test extends BaseTest
{
    private Schema $schema;

    protected function setUp(): void
    {
        parent::setUp();

        if (! $this->isOrmV2()) {
            $this->markTestSkipped('Test for CycleORM v2');
        }

        $this->schema = new Schema([
            User::class => [
                SchemaInterface::SOURCE => 'users',
                SchemaInterface::ROLE => 'user',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'email', 'balance'],
                SchemaInterface::TYPECAST => ['id' => 'int', 'balance' => 'float'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [
                    'tags' => [
                        Relation::TYPE => Relation::MANY_TO_MANY,
                        Relation::TARGET => Tag::class,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::THROUGH_ENTITY => TagContext::class,
                            Relation::INNER_KEY => 'id',
                            Relation::OUTER_KEY => 'id',
                            Relation::THROUGH_INNER_KEY => 'user_id',
                            Relation::THROUGH_OUTER_KEY => 'tag_id',
                        ],
                    ],
                    'tag' => [
                        Relation::TYPE => Relation::BELONGS_TO,
                        Relation::TARGET => Tag::class,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::INNER_KEY => 'tag_id',
                            Relation::OUTER_KEY => 'id',
                        ],
                    ],
                ],
                123 => 'value',
            ],
        ]);
    }

    public function testObjectShouldBeConvertedToArray()
    {
        $this->assertEquals([
            'user' => [
                SchemaInterface::ENTITY => User::class,
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::SOURCE => 'users',
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'email', 'balance'],
                SchemaInterface::RELATIONS => [
                    'tags' => [
                        Relation::TYPE => Relation::MANY_TO_MANY,
                        Relation::TARGET => 'Cycle\Schema\Renderer\Tests\Fixture\Tag',
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::THROUGH_ENTITY => 'Cycle\Schema\Renderer\Tests\Fixture\TagContext',
                            Relation::INNER_KEY => 'id',
                            Relation::OUTER_KEY => 'id',
                            Relation::THROUGH_INNER_KEY => 'user_id',
                            Relation::THROUGH_OUTER_KEY => 'tag_id',
                        ],
                    ],
                    'tag' => [
                        Relation::TYPE => 12,
                        Relation::TARGET => 'Cycle\Schema\Renderer\Tests\Fixture\Tag',
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::INNER_KEY => 'tag_id',
                            Relation::OUTER_KEY => 'id',
                        ],
                    ],
                ],
                SchemaInterface::TYPECAST => [
                    'id' => 'int',
                    'balance' => 'float',
                ],
                SchemaInterface::SCHEMA => [],
                123 => 'value',
            ],
        ], (new SchemaToArrayConverter())->convert($this->schema));
    }
}
