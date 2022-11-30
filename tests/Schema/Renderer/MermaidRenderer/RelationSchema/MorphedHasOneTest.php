<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\RelationSchema;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

class MorphedHasOneTest extends BaseTest
{
    public function testBelongsTo(): void
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
            best_comments(MoHO: comment)
        }
        post --> comment : best_comments
        class comment {
            string id
            string parent_id
            string parent_type
            string message
        }


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
                SchemaInterface::RELATIONS => [
                    'best_comments' => [
                        Relation::TYPE => Relation::MORPHED_HAS_ONE,
                        Relation::TARGET => 'comment',
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::INNER_KEY => 'id',
                            Relation::OUTER_KEY => 'parent_id',
                            Relation::MORPH_KEY => 'parent_type',
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
            'comment' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'comment',
                SchemaInterface::PRIMARY_KEY => ['id'],
                SchemaInterface::FIND_BY_KEYS => ['id'],
                SchemaInterface::COLUMNS => ['id', 'parent_id', 'parent_type', 'message'],
                SchemaInterface::RELATIONS => [],
                SchemaInterface::TYPECAST => [],
                SchemaInterface::SCHEMA => [],
            ],
        ];
    }
}
