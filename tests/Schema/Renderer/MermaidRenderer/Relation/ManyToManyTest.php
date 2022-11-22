<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;
use Cycle\Schema\Renderer\Tests\Fixture\PostTag;
use Cycle\Schema\Renderer\Tests\Fixture\Tag;

class ManyToManyTest extends BaseTest
{
    public function testManyToMany(): void
    {
        $mermaid = new MermaidRenderer();

        $this->assertSame(<<<SCHEMA

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

        class postTag {
            int id
            int post_id
            int tag_id
        }

        class tag {
            int id
            string label
            datetime created_at
            posts(MtM: post)
        }
        tag --* post : posts
        postTag ..> tag : tag.posts
        postTag ..> post : tag.posts

        SCHEMA, $mermaid->render($this->getSchema()));
    }

    public function getSchema(): array
    {
        return [
            'post' => [
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
            PostTag::class => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'post_tag',
                SchemaInterface::PRIMARY_KEY => ['id'],
                SchemaInterface::FIND_BY_KEYS => ['id'],
                SchemaInterface::COLUMNS => [
                    'id' => 'id',
                    'post_id' => 'post_id',
                    'tag_id' => 'tag_id',
                ],
                SchemaInterface::RELATIONS => [],
                SchemaInterface::SCOPE => null,
                SchemaInterface::TYPECAST => [
                    'id' => 'int',
                    'post_id' => 'int',
                    'tag_id' => 'int',
                ],
                SchemaInterface::SCHEMA => [],
            ],
            Tag::class => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'tag',
                SchemaInterface::PRIMARY_KEY => ['id'],
                SchemaInterface::FIND_BY_KEYS => ['id'],
                SchemaInterface::COLUMNS => [
                    'id' => 'id',
                    'label' => 'label',
                    'created_at' => 'created_at',
                ],
                SchemaInterface::RELATIONS => [
                    'posts' => [
                        Relation::TYPE => Relation::MANY_TO_MANY,
                        Relation::TARGET => 'post',
                        Relation::COLLECTION_TYPE => 'array',
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::WHERE => [],
                            Relation::ORDER_BY => [],
                            Relation::INNER_KEY => ['id'],
                            Relation::OUTER_KEY => ['id'],
                            Relation::THROUGH_ENTITY => PostTag::class,
                            Relation::THROUGH_INNER_KEY => 'tag_id',
                            Relation::THROUGH_OUTER_KEY => 'post_id',
                            Relation::THROUGH_WHERE => [],
                        ],
                    ],
                ],
                SchemaInterface::SCOPE => null,
                SchemaInterface::TYPECAST => [
                    'id' => 'int',
                    'created_at' => 'datetime',
                ],
                SchemaInterface::SCHEMA => [],
            ],
        ];
    }
}
