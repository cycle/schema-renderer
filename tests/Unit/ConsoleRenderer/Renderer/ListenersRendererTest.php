<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\ListenersRenderer;
use Testo\Assert;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

#[Test]
final class ListenersRendererTest
{
    private Formatter $formatter;

    public function testRenderNotExistsProperty(): void
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [], 'baz');

        Assert::null($result);
    }

    public function testRenderStringProperty(): void
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => 'bar',
        ], 'baz');

        Assert::same($result, <<<'OUTPUT'
          Foo:
             bar
OUTPUT);
    }

    public function testRenderArrayProperty(): void
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => ['foo-listener', 'bar-listener'],
        ], 'baz');

        Assert::same($result, <<<'OUTPUT'
                      Foo:
                         foo-listener
                         bar-listener
            OUTPUT);
    }

    public function testRenderAssocArrayProperty(): void
    {
        $renderer = new ListenersRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => [
                ['foo-listener', []],
                ['bar-listener', ['baz' => 'bar', 'baz1', 'baz2']],
                ['baz-listener', 'baz'],
            ],
        ], 'baz');

        Assert::same($result, <<<'OUTPUT'
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

    #[BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
    }
}
