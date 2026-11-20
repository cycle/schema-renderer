<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\ConsoleRenderer\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\ColumnsRenderer;
use Testo\Assert;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

#[Test]
final class ColumnsRendererTest
{
    private Formatter $formatter;

    public function testRenderNullValue(): void
    {
        $renderer = new ColumnsRenderer();

        $result = $renderer->render($this->formatter, [SchemaInterface::COLUMNS => null], 'bar');

        Assert::same($result, '       Fields: not defined');
    }

    public function testRenderStringValue(): void
    {
        $renderer = new ColumnsRenderer();

        $result = $renderer->render($this->formatter, [SchemaInterface::COLUMNS => 'foo'], 'bar');

        Assert::same($result, '       Fields: not defined');
    }

    public function testRenderArrayWithoutTypecastValue(): void
    {
        $renderer = new ColumnsRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::COLUMNS => [
                'id' => 'integer',
                'title' => 'string(36)',
                'description' => 'string',
            ],
        ], 'bar');

        Assert::same($result, <<<'OUT'
       Fields:
               (property -> db.field -> typecast)
               id -> integer
               title -> string(36)
               description -> string
OUT);
    }

    public function testRenderArrayWithTypecastValue(): void
    {
        $renderer = new ColumnsRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::COLUMNS => [
                'id' => 'integer',
                'title' => 'string',
                'slug' => 'string',
                'json' => 'json',
                'description' => 'text',
            ],
            SchemaInterface::TYPECAST => [
                'id' => 'int',
                'title' => null,
                'slug' => '',
                'json' => ['Json', 'cast'],
                'description' => [],
            ],
        ], 'bar');

        Assert::same($result, <<<'OUT'
       Fields:
               (property -> db.field -> typecast)
               id -> integer -> int
               title -> string
               slug -> string
               json -> json -> Json::cast
               description -> text
OUT);
    }

    public function testTypecastOfPropertyWinsOverTypecastOfField(): void
    {
        $renderer = new ColumnsRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::COLUMNS => ['id' => 'user_id'],
            SchemaInterface::TYPECAST => ['id' => 'int', 'user_id' => 'string'],
        ], 'bar');

        Assert::same($result, <<<'OUT'
       Fields:
               (property -> db.field -> typecast)
               id -> user_id -> int
OUT);
    }

    #[BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
    }
}
