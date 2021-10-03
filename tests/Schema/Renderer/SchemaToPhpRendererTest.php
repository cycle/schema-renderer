<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Relation;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\PhpSchemaRenderer;
use Cycle\Schema\Renderer\Tests\Fixture\Tag;
use Cycle\Schema\Renderer\Tests\Fixture\TagContext;
use Cycle\Schema\Renderer\Tests\Fixture\User;
use PHPUnit\Framework\TestCase;

final class SchemaToPhpRendererTest extends TestCase
{
    private array $schema;

    protected function setUp(): void
    {
        parent::setUp();

        $schema = new Schema([
            User::class => [
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
                            Relation::THROUGH_ENTITY => 'tag_context',
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
                ]
            ],
            Tag::class => [
                SchemaInterface::ROLE => 'tag',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'tag',
                SchemaInterface::PRIMARY_KEY => ['id', 'name'],
                SchemaInterface::COLUMNS => ['id', 'name'],
                SchemaInterface::TYPECAST => ['id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [
                    'user' => [
                        Relation::TYPE => Relation::BELONGS_TO,
                        Relation::TARGET => User::class,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::INNER_KEY => 'user_id',
                            Relation::OUTER_KEY => 'id',
                        ],
                    ]
                ]
            ],
            TagContext::class => [
                SchemaInterface::ROLE => 'tag_context',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'tag_user_map',
                SchemaInterface::COLUMNS => [],
                SchemaInterface::TYPECAST => ['id' => 'int', 'user_id' => 'int', 'tag_id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => []
            ]
        ]);

        $this->schema = (new SchemaToArrayConverter())->convert($schema);
    }

    public function testRenderSchemaToPhpCode(): void
    {
        $renderer = new PhpSchemaRenderer();
        $expected = file_get_contents(__DIR__ . '/Fixture/generated_schema.stub.php');

        $this->assertSame(
            $expected,
            $renderer->render($this->schema)
        );
    }

    public function testRenderEmptySchemaToPhpCode(): void
    {
        $renderer = new PhpSchemaRenderer();

        $this->assertSame(
            <<<PHP
                <?php

                declare(strict_types=1);

                use Cycle\ORM\Relation;
                use Cycle\ORM\SchemaInterface as Schema;

                return [];

                PHP
            ,
            $renderer->render([])
        );
    }

    public function testRenderSchemaWithCustomPropertyToPhpCode(): void
    {
        $renderer = new PhpSchemaRenderer();

        $this->assertSame(
            <<<PHP
                <?php

                declare(strict_types=1);

                use Cycle\ORM\Relation;
                use Cycle\ORM\SchemaInterface as Schema;

                return [
                    'Cycle\Schema\Renderer\Tests\Fixture\TagContext' => [
                        Schema::ROLE => 'tag_context',
                        Schema::MAPPER => 'Cycle\\\\ORM\\\\Mapper\\\\Mapper',
                        Schema::DATABASE => 'default',
                        Schema::TABLE => 'tag_user_map',
                        Schema::COLUMNS => [],
                        Schema::TYPECAST => [
                            'id' => 'int',
                            'user_id' => 'int',
                            'tag_id' => 'int',
                        ],
                        Schema::SCHEMA => [],
                        '100' => 'Hello world',
                        'hello' => 'world',
                    ],
                ];

                PHP
            ,
            $renderer->render([
                TagContext::class => [
                    SchemaInterface::ROLE => 'tag_context',
                    SchemaInterface::MAPPER => Mapper::class,
                    SchemaInterface::DATABASE => 'default',
                    SchemaInterface::TABLE => 'tag_user_map',
                    SchemaInterface::COLUMNS => [],
                    SchemaInterface::TYPECAST => ['id' => 'int', 'user_id' => 'int', 'tag_id' => 'int'],
                    SchemaInterface::SCHEMA => [],
                    100 => 'Hello world',
                    'hello' => 'world'
                ]
            ])
        );
    }
}
