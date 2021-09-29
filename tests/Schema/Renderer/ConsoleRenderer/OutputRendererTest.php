<?php

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatters\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatters\StyledFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\OutputRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderers\TitleRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\Fixtures\Tag;
use Cycle\Schema\Renderer\Tests\Fixtures\TagContext;
use PHPUnit\Framework\TestCase;

class OutputRendererTest extends TestCase
{

    private array $schemaArray;

    protected function setUp(): void
    {
        parent::setUp();

        $schema = new Schema([
            Tag::class => [
                SchemaInterface::ROLE => 'tag',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'tag',
                SchemaInterface::PRIMARY_KEY => ['id', 'name'],
                SchemaInterface::COLUMNS => ['id', 'name'],
                SchemaInterface::TYPECAST => ['id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => []
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

    public function testSchemaShouldBeRenderedByGivenRenderers(): void
    {
        $renderer = new OutputRenderer(
            $this->schemaArray, new StyledFormatter(), [
                new TitleRenderer()
            ]
        );

        $this->assertSame(
           "[35m[tag][39m :: [32mdefault[39m.[32mtag[39m

[35m[tag_context][39m :: [32mdefault[39m.[32mtag_user_map[39m"
,
            implode("\n\n", iterator_to_array($renderer))
        );
    }

    public function testSchemaShouldBeRenderedByGivenFormatter(): void
    {
        $renderer = new OutputRenderer(
            $this->schemaArray, new PlainFormatter(), [
                new TitleRenderer()
            ]
        );

        $this->assertSame(
            "[tag] :: default.tag

[tag_context] :: default.tag_user_map"
            ,
            implode("\n\n", iterator_to_array($renderer))
        );
    }
}
