<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\SchemaToArrayConverter;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Relation;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\Fixtures\Tag;
use Cycle\Schema\Renderer\Tests\Fixtures\TagContext;
use Cycle\Schema\Renderer\Tests\Fixtures\User;
use Testo\Assert;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

#[Test]
final class CycleOrmV2Test
{
    private Schema $schema;

    public function testObjectShouldBeConvertedToArray(): void
    {
        Assert::equals((new SchemaToArrayConverter())->convert($this->schema), [
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
                        Relation::TARGET => 'Cycle\Schema\Renderer\Tests\Fixtures\Tag',
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::THROUGH_ENTITY => 'Cycle\Schema\Renderer\Tests\Fixtures\TagContext',
                            Relation::INNER_KEY => 'id',
                            Relation::OUTER_KEY => 'id',
                            Relation::THROUGH_INNER_KEY => 'user_id',
                            Relation::THROUGH_OUTER_KEY => 'tag_id',
                        ],
                    ],
                    'tag' => [
                        Relation::TYPE => 12,
                        Relation::TARGET => 'Cycle\Schema\Renderer\Tests\Fixtures\Tag',
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
        ]);
    }

    #[BeforeTest]
    protected function setUp(): void
    {
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
}
