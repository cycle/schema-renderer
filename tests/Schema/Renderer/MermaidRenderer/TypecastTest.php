<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;
use Cycle\Schema\Renderer\Tests\Fixture\Typecast\PostContent;
use Cycle\Schema\Renderer\Tests\Fixture\Typecast\PostImage;
use Cycle\Schema\Renderer\Tests\Fixture\Typecast\PostSlug;

class TypecastTest extends BaseTest
{
    public function testTypecast(): void
    {
        $mermaid = new MermaidRenderer();

        $this->assertSame(<<<SCHEMA

        classDiagram
        class post {
            int id
            [PostSlug::class, 'cast'] slug
            Closure title
            bool public
            [PostContent::class, 'cast'] content
            PostImage::class image
            datetime created_at
            datetime updated_at
            datetime published_at
            datetime deleted_at
            int user_id
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
                    'image' => 'image',
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
                    'slug' => [new PostSlug(), 'cast'],
                    'title' => fn() => 'string',
                    'content' => [PostContent::class, 'cast'],
                    'image' => PostImage::class,
                    'created_at' => 'datetime',
                    'updated_at' => 'datetime',
                    'published_at' => 'datetime',
                    'deleted_at' => 'datetime',
                    'user_id' => 'int',
                ],
                SchemaInterface::SCHEMA => [],
            ],
        ];
    }
}
