<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\ConsoleRenderer\Renderer;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\RelationsRenderer;
use Testo\Assert;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

#[Test]
final class RelationsRendererTest
{
    private Formatter $formatter;
    private RelationsRenderer $renderer;

    public function testRenderBelongsToRelation(): void
    {
        $result = $this->renderer->render($this->formatter, [
            SchemaInterface::RELATIONS => [
                'user' => [
                    Relation::TYPE => Relation::BELONGS_TO,
                    Relation::TARGET => 'guest',
                    Relation::SCHEMA => [
                        Relation::NULLABLE => false,
                        Relation::CASCADE => true,
                        Relation::INNER_KEY => ['parent_key1', 'parent_key2'],
                        Relation::OUTER_KEY => ['key1', 'key2'],
                    ],
                ],
            ],
        ], 'foo');

        Assert::same($result, <<<'OUTPUT'
    Relations:
     foo->user belongs to guest, default loading, cascaded
       not null foo.[parent_key1, parent_key2] <==> guest.[key1, key2]
OUTPUT);
    }

    public function testRenderHasOneRelation(): void
    {
        $result = $this->renderer->render($this->formatter, [
            SchemaInterface::RELATIONS => [
                'user' => [
                    Relation::TYPE => Relation::HAS_ONE,
                    Relation::TARGET => 'guest',
                    Relation::SCHEMA => [
                        Relation::CASCADE => true,
                        Relation::INNER_KEY => ['key1', 'key2'],
                        Relation::OUTER_KEY => ['parent_key1', 'parent_key2'],
                    ],
                ],
            ],
        ], 'foo');

        Assert::same($result, <<<'OUTPUT'
    Relations:
     foo->user has one guest, default loading, cascaded
       n/a foo.[key1, key2] <==> guest.[parent_key1, parent_key2]
OUTPUT);
    }

    public function testRenderRefersToRelation(): void
    {
        $result = $this->renderer->render($this->formatter, [
            SchemaInterface::RELATIONS => [
                'user' => [
                    Relation::TYPE => Relation::REFERS_TO,
                    Relation::TARGET => 'tag',
                    Relation::SCHEMA => [
                        Relation::CASCADE => true,
                        Relation::INNER_KEY => 'tag_id',
                        Relation::OUTER_KEY => 'id',
                    ],
                ],
            ],
        ], 'foo');

        Assert::same($result, <<<'OUTPUT'
    Relations:
     foo->user refers to tag, default loading, cascaded
       n/a foo.tag_id <==> tag.id
OUTPUT);
    }

    public function testRenderEmbeddedRelation(): void
    {
        $result = $this->renderer->render($this->formatter, [
            SchemaInterface::RELATIONS => [
                'user' => [
                    Relation::TYPE => Relation::EMBEDDED,
                    Relation::TARGET => 'user:credentials',
                    Relation::LOAD => Relation::LOAD_PROMISE,
                    Relation::SCHEMA => [],
                ],
            ],
        ], 'foo');

        Assert::same($result, <<<'OUTPUT'
    Relations:
     foo->user has embedded user:credentials, lazy loading, not cascaded
       n/a foo.? <==> user:credentials.?
OUTPUT);
    }

    public function testRenderHasManyRelation(): void
    {
        $result = $this->renderer->render($this->formatter, [
            SchemaInterface::RELATIONS => [
                'user' => [
                    Relation::TYPE => Relation::HAS_MANY,
                    Relation::TARGET => 'tag',
                    Relation::SCHEMA => [
                        Relation::NULLABLE => true,
                        Relation::CASCADE => true,
                        Relation::INNER_KEY => 'id',
                        Relation::OUTER_KEY => 'user_id',
                    ],
                ],
            ],
        ], 'foo');

        Assert::same($result, <<<'OUTPUT'
    Relations:
     foo->user has many tag, default loading, cascaded
       nullable foo.id <==> tag.user_id
OUTPUT);
    }

    public function testRenderManyToManyRelation(): void
    {
        $result = $this->renderer->render($this->formatter, [
            SchemaInterface::RELATIONS => [
                'user' => [
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
            ],
        ], 'foo');

        Assert::same($result, <<<'OUTPUT'
    Relations:
     foo->user many to many tag, default loading, cascaded
       n/a foo.id <= tag_context.user_id | tag_context.tag_id => tag.id
OUTPUT);
    }

    public function testRenderBelongsToMorphedRelation(): void
    {
        $result = $this->renderer->render($this->formatter, [
            SchemaInterface::RELATIONS => [
                'user' => [
                    Relation::TYPE => Relation::BELONGS_TO_MORPHED,
                    Relation::TARGET => 'image',
                    Relation::LOAD => Relation::LOAD_PROMISE,

                    Relation::SCHEMA => [
                        Relation::NULLABLE => true,
                        Relation::CASCADE => true,
                        Relation::OUTER_KEY => 'id',
                        Relation::INNER_KEY => 'parentId',
                        Relation::MORPH_KEY => 'parentType',
                    ],
                ],
            ],
        ], 'foo');

        Assert::same($result, <<<'OUTPUT'
    Relations:
     foo->user belongs to morphed image,   morphed key: parentType, lazy loading, cascaded
       nullable foo.parentId <==> image.id
OUTPUT);
    }

    public function testRenderHasOneMorphedRelation(): void
    {
        $result = $this->renderer->render($this->formatter, [
            SchemaInterface::RELATIONS => [
                'image' => [
                    Relation::TYPE => Relation::MORPHED_HAS_ONE,
                    Relation::TARGET => 'user',
                    Relation::SCHEMA => [
                        Relation::CASCADE => true,
                        Relation::INNER_KEY => 'id',
                        Relation::OUTER_KEY => 'parentId',
                        Relation::MORPH_KEY => 'parentType',
                    ],
                ],
            ],
        ], 'foo');

        Assert::same($result, <<<'OUTPUT'
    Relations:
     foo->image morphed has one user,   morphed key: parentType, default loading, cascaded
       n/a foo.id <==> user.parentId
OUTPUT);
    }

    public function testRenderHasManyMorphedRelation(): void
    {
        $result = $this->renderer->render($this->formatter, [
            SchemaInterface::RELATIONS => [
                'post' => [
                    Relation::TYPE => Relation::MORPHED_HAS_MANY,
                    Relation::TARGET => 'comment',
                    Relation::SCHEMA => [
                        Relation::CASCADE => true,
                        Relation::INNER_KEY => 'id',
                        Relation::OUTER_KEY => 'parent_id',
                        Relation::MORPH_KEY => 'parent_type',
                    ],
                ],
            ],
        ], 'foo');

        Assert::same($result, <<<'OUTPUT'
    Relations:
     foo->post morphed has many comment,   morphed key: parent_type, default loading, cascaded
       n/a foo.id <==> comment.parent_id
OUTPUT);
    }

    #[BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
        $this->renderer = new RelationsRenderer();
    }
}
