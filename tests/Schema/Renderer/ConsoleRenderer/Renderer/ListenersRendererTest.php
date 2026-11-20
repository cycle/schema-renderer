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

    #[\Testo\Test]
    public function testRenderNotExistsProperty()
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [], 'baz');

        \Testo\Assert::null($result);
    }

    #[\Testo\Test]
    public function testRenderStringProperty()
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => 'bar',
        ], 'baz');

        \Testo\Assert::same($result, <<<'OUTPUT'
          Foo:
             bar
OUTPUT);
    }

    #[\Testo\Test]
    public function testRenderArrayProperty()
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => ['foo-listener', 'bar-listener'],
        ], 'baz');

        \Testo\Assert::same($result, <<<'OUTPUT'
                      Foo:
                         foo-listener
                         bar-listener
            OUTPUT);
    }

    #[\Testo\Test]
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

        \Testo\Assert::same($result, <<<'OUTPUT'
                      Foo:
                         foo-listener
                         bar-listener
                          - baz : bar
                          - baz1
                          - baz2
                         baz-listener
                          - baz
            OUTPUT);
    }

    #[\Testo\Lifecycle\BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
    }
}
