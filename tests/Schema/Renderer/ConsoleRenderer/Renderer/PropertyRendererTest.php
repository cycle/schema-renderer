<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\PropertyRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

class PropertyRendererTest extends BaseTest
{
    private Formatter $formatter;

    #[\Testo\Lifecycle\BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
    }

    #[\Testo\Test]
    public function testRenderNotExistsProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [], 'baz');

        \Testo\Assert::null($result);
    }

    #[\Testo\Test]
    public function testRenderNotExistsRequiredProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo', true);

        $result = $renderer->render($this->formatter, [], 'baz');

        \Testo\Assert::same($result, '          Foo: not defined');
    }

    #[\Testo\Test]
    public function testRenderNullNotRequiredProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => null,
        ], 'baz');

        \Testo\Assert::null($result);
    }

    #[\Testo\Test]
    public function testRenderNullRequiredProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo', true);

        $result = $renderer->render($this->formatter, [
            1 => null,
        ], 'baz');

        \Testo\Assert::same($result, '          Foo: not defined');
    }

    #[\Testo\Test]
    public function testRenderStringProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => 'bar',
        ], 'baz');

        \Testo\Assert::same($result, '          Foo: bar');
    }

    #[\Testo\Test]
    public function testRenderSingleItemArrayProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => ['hello'],
        ], 'baz');

        \Testo\Assert::same($result, '          Foo: hello');
    }

    #[\Testo\Test]
    public function testRenderEmptyArrayProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => [],
        ], 'baz');

        \Testo\Assert::same($result, '          Foo: []');
    }

    #[\Testo\Test]
    public function testRenderArrayProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => ['hello', 'world', 'baz'],
        ], 'baz');

        \Testo\Assert::same($result, <<<'OUT'
          Foo: hello
               world
               baz
OUT);
    }
}
