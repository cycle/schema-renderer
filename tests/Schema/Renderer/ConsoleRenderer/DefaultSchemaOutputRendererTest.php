<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Relation;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\DefaultSchemaOutputRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatters\StyledFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderers\PropertyRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\Fixtures\Tag;
use Cycle\Schema\Renderer\Tests\Fixtures\TagContext;
use Cycle\Schema\Renderer\Tests\Fixtures\User;
use PHPUnit\Framework\TestCase;

class DefaultSchemaOutputRendererTest extends TestCase
{
    private array $schemaArray;

    protected function setUp(): void
    {
        parent::setUp();

        $schema = new Schema([
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

        $this->schemaArray = (new SchemaToArrayConverter())->convert($schema);
    }

    public function testSchemaShouldBeRendered(): void
    {
        $renderer = new DefaultSchemaOutputRenderer(
            $this->schemaArray, new StyledFormatter()
        );

        $this->assertSame(
            file_get_contents(__DIR__ . '/../Fixtures/console_output.txt'),
            implode("\n\n", iterator_to_array($renderer))
        );
    }

    public function testSchemaWithExtraPropertiesShouldBeRendered(): void
    {
        $renderer = new DefaultSchemaOutputRenderer(
            $this->schemaArray, new StyledFormatter()
        );

        $renderer->addRenderer(new PropertyRenderer(SchemaInterface::SOURCE, 'Source'));

        $this->assertSame(
            file_get_contents(__DIR__ . '/../Fixtures/console_output_with_custom_properties.txt'),
            implode("\n\n", iterator_to_array($renderer))
        );
    }
}
