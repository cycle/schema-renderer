<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\ListenersRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class ListenersRendererTest extends BaseTest
{
    private Formatter $formatter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatter = new PlainFormatter();
    }

    public function testRenderNotExistsProperty()
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [], 'baz');

        $this->assertNull($result);
    }

    public function testRenderStringProperty()
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => 'bar',
        ], 'baz');

        $this->assertSame(
            <<<'OUTPUT'
          Foo:
             bar
OUTPUT
            ,
            $result
        );
    }

    public function testRenderArrayProperty()
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => ['foo-listener', 'bar-listener'],
        ], 'baz');

        $this->assertSame(
            <<<'OUTPUT'
                      Foo:
                         foo-listener
                         bar-listener
            OUTPUT
            ,
            $result
        );
    }

    public function testRenderAssocArrayProperty()
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => [
                ['foo-listener', []],
                ['bar-listener', ['baz' => 'bar', 'baz1', 'baz2']],
                ['baz-listener', 'baz'],
            ],
        ], 'baz');

        $this->assertSame(
            <<<'OUTPUT'
                      Foo:
                         foo-listener
                         bar-listener
                          - baz : bar
                          - baz1
                          - baz2
                         baz-listener
                          - baz
            OUTPUT
            ,
            $result
        );
    }
}
