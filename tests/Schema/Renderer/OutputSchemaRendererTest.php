<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Relation;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\PropertyRenderer;
use Cycle\Schema\Renderer\OutputSchemaRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\Fixture\Tag;
use Cycle\Schema\Renderer\Tests\Fixture\TagContext;
use Cycle\Schema\Renderer\Tests\Fixture\User;
use PHPUnit\Framework\TestCase;

final class OutputSchemaRendererTest extends TestCase
{
    private array $schemaArray;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schemaArray = [
            User::class => [
                SchemaInterface::ROLE => 'user',
                SchemaInterface::SOURCE => 'test',
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
                ],
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
                    ],
                ],
            ],
            TagContext::class => [
                SchemaInterface::ROLE => 'tag_context',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'tag_user_map',
                SchemaInterface::COLUMNS => [],
                SchemaInterface::TYPECAST => ['id' => 'int', 'user_id' => 'int', 'tag_id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
                'my_custom_property' => 'super_value',
                25 => 'super_value',
            ],
        ];
    }

    public function testSchemaShouldBeRendered(): void
    {
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_CONSOLE_COLOR);

        $expected = file_get_contents(__DIR__ . '/Fixture/console_output.stub.txt');

        $this->assertSame(
            $expected,
            $renderer->render($this->schemaArray)
        );
    }

    public function testPlainFormat(): void
    {
        $schema = new Schema($this->schemaArray);
        $schemaArray = (new SchemaToArrayConverter())->convert($schema);
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT);

        $expected = file_get_contents(__DIR__ . '/Fixture/console_output_plain.stub.txt');

        $this->assertSame(
            $expected,
            $renderer->render($schemaArray)
        );
    }

    public function testSchemaWithExtraPropertiesShouldBeRendered(): void
    {
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_CONSOLE_COLOR);
        $renderer->addRenderer(new PropertyRenderer(SchemaInterface::SOURCE, 'Source'));

        $expected = file_get_contents(__DIR__ . '/Fixture/console_output_with_custom_properties.stub.txt');

        $this->assertSame(
            $expected,
            $renderer->render($this->schemaArray)
        );
    }
}
