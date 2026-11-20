<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\StyledFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\OutputRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\TitleRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\BaseTest;
use Cycle\Schema\Renderer\Tests\Fixture\Tag;
use Cycle\Schema\Renderer\Tests\Fixture\TagContext;

class OutputRendererTest extends BaseTest
{
    private array $schemaArray;

    #[\Testo\Lifecycle\BeforeTest]
    protected function setUp(): void
    {
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
                SchemaInterface::RELATIONS => [],
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
            ],
        ]);

        $this->schemaArray = (new SchemaToArrayConverter())->convert($schema);
    }

    #[\Testo\Test]
    public function testSchemaShouldBeRenderedByGivenRenderers(): void
    {
        $renderer = new OutputRenderer(new StyledFormatter(), [
            new TitleRenderer(),
        ]);

        \Testo\Assert::same($renderer->render($this->schemaArray), <<<TEXT
            [35m[tag][39m :: [32mdefault[39m.[32mtag[39m

            [35m[tag_context][39m :: [32mdefault[39m.[32mtag_user_map[39m


            TEXT);
    }

    #[\Testo\Test]
    public function testSchemaShouldBeRenderedByGivenFormatter(): void
    {
        $renderer = new OutputRenderer(new PlainFormatter(), [
            new TitleRenderer(),
        ]);

        \Testo\Assert::same($renderer->render($this->schemaArray), <<<TEXT
            [tag] :: default.tag

            [tag_context] :: default.tag_user_map


            TEXT);
    }
}
