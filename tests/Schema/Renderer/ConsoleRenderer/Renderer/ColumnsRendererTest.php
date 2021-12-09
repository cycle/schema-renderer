<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\ColumnsRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

class ColumnsRendererTest extends BaseTest
{
    private Formatter $formatter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatter = new PlainFormatter();
    }

    public function testRenderNullValue()
    {
        $renderer = new ColumnsRenderer();

        $result = $renderer->render($this->formatter, [SchemaInterface::COLUMNS => null], 'bar');

        $this->assertSame('       Fields: not defined', $result);
    }

    public function testRenderStringValue()
    {
        $renderer = new ColumnsRenderer();

        $result = $renderer->render($this->formatter, [SchemaInterface::COLUMNS => 'foo'], 'bar');

        $this->assertSame('       Fields: not defined', $result);
    }

    public function testRenderArrayWithoutTypecastValue()
    {
        $renderer = new ColumnsRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::COLUMNS => [
                'id' => 'integer',
                'title' => 'string(36)',
                'description' => 'string',
            ],
        ], 'bar');

        $this->assertSame(
            <<<'OUT'
       Fields:
               (property -> db.field -> typecast)
               id -> integer
               title -> string(36)
               description -> string
OUT
            ,
            $result
        );
    }

    public function testRenderArrayWithTypecastValue()
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

        $this->assertSame(
            <<<'OUT'
       Fields:
               (property -> db.field -> typecast)
               id -> integer -> int
               title -> string
               slug -> string
               json -> json -> Json::cast
               description -> text
OUT
            ,
            $result
        );
    }
}
