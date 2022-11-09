<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

class MermaidRendererTest extends BaseTest
{
    public function testRender(): void
    {
        $mermaid = new MermaidRenderer();

        $this->assertSame(<<<DIAGRAM

        erDiagram
        comment {
            int id
            bool public
            string content
            datetime created_at
            datetime updated_at
            datetime published_at
            datetime deleted_at
            int user_id
            int post_id
        }
        comment ||--|| user : belongs_to
        comment ||--|| post : belongs_to
        post {
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
        post ||--|| user : belongs_to
        post ||--|{ postTag : many_to_many
        tag ||--|{ postTag : many_to_many
        post ||--|{ comment : has_many
        post ||--|| image : morphed_has_one
        postTag {
            int id
            int post_id
            int tag_id
        }

        tag {
            int id
            string label
            datetime created_at
        }
        tag ||--|{ postTag : many_to_many
        post ||--|{ postTag : many_to_many
        user {
            int id
            string login
            string password_hash
            datetime created_at
            datetime updated_at
        }
        user ||--o{ post : has_many
        user ||--|{ comment : has_many
        user ||--|| image : morphed_has_one
        user ||--|{ foo : morphed_has_many
        image {
            string id
            string parent_id
            string parent_type
            string url
        }

        foo {
            string id
            string parent_id
            string parent_type
            string url
        }

        bar {
            string id
            string title
            string body
        }
        bar ||--|| foo : belongs_to_morphed

        DIAGRAM, $mermaid->render($this->getSchema()));
    }

    public function getSchema(): array
    {
        return [
            'comment' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'comment',
                SchemaInterface::PRIMARY_KEY => ['id'],
                SchemaInterface::FIND_BY_KEYS => ['id'],
                SchemaInterface::COLUMNS => [
                    'id' => 'id',
                    'public' => 'public',
                    'content' => 'content',
                    'created_at' => 'created_at',
                    'updated_at' => 'updated_at',
                    'published_at' => 'published_at',
                    'deleted_at' => 'deleted_at',
                    'user_id' => 'user_id',
                    'post_id' => 'post_id',
                ],
                SchemaInterface::RELATIONS => [
                    'user' => [
                        Relation::TYPE => Relation::BELONGS_TO,
                        Relation::TARGET => 'user',
                        Relation::LOAD => Relation::LOAD_EAGER,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::NULLABLE => false,
                            Relation::INNER_KEY => 'user_id',
                            Relation::OUTER_KEY => ['id'],
                        ],
                    ],
                    'post' => [
                        Relation::TYPE => Relation::BELONGS_TO,
                        Relation::TARGET => 'post',
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::NULLABLE => false,
                            Relation::INNER_KEY => 'post_id',
                            Relation::OUTER_KEY => ['id'],
                        ],
                    ],
                ],
                SchemaInterface::TYPECAST => [
                    'id' => 'int',
                    'public' => 'bool',
                    'created_at' => 'datetime',
                    'updated_at' => 'datetime',
                    'published_at' => 'datetime',
                    'deleted_at' => 'datetime',
                    'user_id' => 'int',
                    'post_id' => 'int',
                ],
                SchemaInterface::SCHEMA => [],
            ],
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
                SchemaInterface::RELATIONS => [
                    'user' => [
                        Relation::TYPE => Relation::BELONGS_TO,
                        Relation::TARGET => 'user',
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::NULLABLE => false,
                            Relation::INNER_KEY => 'user_id',
                            Relation::OUTER_KEY => ['id'],
                        ],
                    ],
                    'tags' => [
                        Relation::TYPE => Relation::MANY_TO_MANY,
                        Relation::TARGET => 'tag',
                        Relation::COLLECTION_TYPE => 'array',
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::NULLABLE => false,
                            Relation::WHERE => [],
                            Relation::ORDER_BY => [],
                            Relation::INNER_KEY => ['id'],
                            Relation::OUTER_KEY => ['id'],
                            Relation::THROUGH_ENTITY => 'postTag',
                            Relation::THROUGH_INNER_KEY => 'post_id',
                            Relation::THROUGH_OUTER_KEY => 'tag_id',
                            Relation::THROUGH_WHERE => [],
                        ],
                    ],
                    'comments' => [
                        Relation::TYPE => Relation::HAS_MANY,
                        Relation::TARGET => 'comment',
                        Relation::COLLECTION_TYPE => 'array',
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::NULLABLE => false,
                            Relation::WHERE => [],
                            Relation::ORDER_BY => [],
                            Relation::INNER_KEY => ['id'],
                            Relation::OUTER_KEY => 'post_id',
                        ],
                    ],
                    'image' => [
                        Relation::TYPE => Relation::MORPHED_HAS_ONE,
                        Relation::TARGET => 'image',
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::INNER_KEY => 'id',
                            Relation::OUTER_KEY => 'parentId',
                            Relation::MORPH_KEY => 'parentType',
                        ],
                    ],
                ],
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
            'postTag' => [
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
            'tag' => [
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
                            Relation::NULLABLE => false,
                            Relation::WHERE => [],
                            Relation::ORDER_BY => [],
                            Relation::INNER_KEY => ['id'],
                            Relation::OUTER_KEY => ['id'],
                            Relation::THROUGH_ENTITY => 'postTag',
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
                        Relation::TARGET => 'post',
                        Relation::COLLECTION_TYPE => 'array',
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::NULLABLE => true,
                            Relation::WHERE => [],
                            Relation::ORDER_BY => [],
                            Relation::INNER_KEY => ['id'],
                            Relation::OUTER_KEY => 'user_id',
                        ],
                    ],
                    'comments' => [
                        Relation::TYPE => Relation::HAS_MANY,
                        Relation::TARGET => 'comment',
                        Relation::COLLECTION_TYPE => 'array',
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::NULLABLE => false,
                            Relation::WHERE => [],
                            Relation::ORDER_BY => [],
                            Relation::INNER_KEY => ['id'],
                            Relation::OUTER_KEY => 'user_id',
                        ],
                    ],
                    'image' => [
                        Relation::TYPE => Relation::MORPHED_HAS_ONE,
                        Relation::TARGET => 'image',
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::INNER_KEY => 'id',
                            Relation::OUTER_KEY => 'parentId',
                            Relation::MORPH_KEY => 'parentType',
                        ],
                    ],
                    'foo' => [
                        Relation::TYPE => Relation::MORPHED_HAS_MANY,
                        Relation::TARGET => 'foo',
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::INNER_KEY => 'id',
                            Relation::OUTER_KEY => 'parentId',
                            Relation::MORPH_KEY => 'parentType',
                        ],
                    ],
                ],
                SchemaInterface::SCOPE => null,
                SchemaInterface::TYPECAST => [
                    'id' => 'int',
                    'created_at' => 'datetime',
                    'updated_at' => 'datetime',
                ],
                SchemaInterface::SCHEMA => [],
            ],
            'image' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'image',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => [
                    'id' => 'id',
                    'parentId' => 'parent_id',
                    'parentType' => 'parent_type',
                    'url' => 'url',
                ],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => []
            ],
            'foo' => [
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'foo',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => [
                    'id' => 'id',
                    'parentId' => 'parent_id',
                    'parentType' => 'parent_type',
                    'url' => 'url',
                ],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => []
            ],
            'bar' => [
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'bar',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => [
                    'id' => 'id',
                    'title' => 'title',
                    'body' => 'body',
                ],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [
                    'foo' => [
                        Relation::TYPE => Relation::BELONGS_TO_MORPHED,
                        Relation::TARGET => 'foo',
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::INNER_KEY => 'id',
                            Relation::OUTER_KEY => 'parentId',
                            Relation::MORPH_KEY => 'parentType',
                        ],
                    ],
                ]
            ],
        ];
    }
}
