<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\RelationSchema;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;
use Cycle\Schema\Renderer\Tests\Fixtures\Post;
use Testo\Assert;
use Testo\Test;

#[Test]
final class HasManyTest
{
    public function testHasMany(): void
    {
        $mermaid = new MermaidRenderer();

        Assert::same($mermaid->render($this->getSchema()), <<<SCHEMA

        classDiagram
        class post {
            int id
            string slug
            string title
            bool public
            string content
            datetime created_at
            datetime updated_at
            datetime published_at
            datetime deleted_at
            int user_id
        }

        class user {
            int id
            string login
            string password_hash
            datetime created_at
            datetime updated_at
            posts(HM: post)
        }
        user --o post : posts

        SCHEMA);
    }

    public function getSchema(): array
    {
        return [
            Post::class => [
                SchemaInterface::ROLE => 'post',
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'post',
                SchemaInterface::PRIMARY_KEY => ['id'],
                SchemaInterface::FIND_BY_KEYS => ['id'],
                SchemaInterface::COLUMNS => [
                    'id' => 'id',
                    'slug' => 'slug',
                    'title' => 'title',
                    'public' => 'public',
                    'content' => 'content',
                    'created_at' => 'created_at',
                    'updated_at' => 'updated_at',
                    'published_at' => 'published_at',
                    'deleted_at' => 'deleted_at',
                    'user_id' => 'user_id',
                ],
                SchemaInterface::RELATIONS => [],
                SchemaInterface::TYPECAST => [
                    'id' => 'int',
                    'public' => 'bool',
                    'created_at' => 'datetime',
                    'updated_at' => 'datetime',
                    'published_at' => 'datetime',
                    'deleted_at' => 'datetime',
                    'user_id' => 'int',
                ],
                SchemaInterface::SCHEMA => [],
            ],
            'user' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => ['id'],
                SchemaInterface::FIND_BY_KEYS => ['id'],
                SchemaInterface::COLUMNS => [
                    'id' => 'id',
                    'login' => 'login',
                    'passwordHash' => 'password_hash',
                    'created_at' => 'created_at',
                    'updated_at' => 'updated_at',
                ],
                SchemaInterface::RELATIONS => [
                    'posts' => [
                        Relation::TYPE => Relation::HAS_MANY,
                        Relation::TARGET => Post::class,
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::NULLABLE => false,
                            Relation::WHERE => [],
                            Relation::INNER_KEY => ['id'],
                            Relation::OUTER_KEY => 'user_id',
                        ],
                    ],
                ],
                SchemaInterface::TYPECAST => [
                    'id' => 'int',
                    'created_at' => 'datetime',
                    'updated_at' => 'datetime',
                ],
                SchemaInterface::SCHEMA => [],
            ],
        ];
    }
}
