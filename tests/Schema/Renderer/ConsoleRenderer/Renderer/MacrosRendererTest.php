<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\MacrosRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class MacrosRendererTest extends BaseTest
{
    private Formatter $formatter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatter = new PlainFormatter();
    }

    public function testRenderNotExistsProperty()
    {
        $renderer = new MacrosRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [], 'baz');

        $this->assertNull($result);
    }

    public function testRenderStringProperty()
    {
        $renderer = new MacrosRenderer(1, 'Foo');

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
        $renderer = new MacrosRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => ['foo-macros', 'bar-macros'],
        ], 'baz');

        $this->assertSame(
            <<<'OUTPUT'
          Foo:
             foo-macros
             bar-macros
OUTPUT
            ,
            $result
        );
    }

    public function testRenderAssocArrayProperty()
    {
        $renderer = new MacrosRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => [
                ['foo-macros', []], ['bar-macros', ['baz' => 'bar', 'baz1', 'baz2']], ['baz-macros', 'baz'],
            ],
        ], 'baz');

        $this->assertSame(
            <<<'OUTPUT'
          Foo:
             foo-macros
             bar-macros
              - baz : bar
              - baz1
              - baz2
             baz-macros
              - baz
OUTPUT
            ,
            $result
        );
    }
}
