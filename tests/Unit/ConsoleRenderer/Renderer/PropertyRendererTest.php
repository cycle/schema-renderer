<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\PropertyRenderer;
use Testo\Assert;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

#[Test]
final class PropertyRendererTest
{
    private Formatter $formatter;

    public function testRenderNotExistsProperty(): void
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [], 'baz');

        Assert::null($result);
    }

    public function testRenderNotExistsRequiredProperty(): void
    {
        $renderer = new PropertyRenderer(1, 'Foo', true);

        $result = $renderer->render($this->formatter, [], 'baz');

        Assert::same($result, '          Foo: not defined');
    }

    public function testRenderNullNotRequiredProperty(): void
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => null,
        ], 'baz');

        Assert::null($result);
    }

    public function testRenderNullRequiredProperty(): void
    {
        $renderer = new PropertyRenderer(1, 'Foo', true);

        $result = $renderer->render($this->formatter, [
            1 => null,
        ], 'baz');

        Assert::same($result, '          Foo: not defined');
    }

    public function testRenderStringProperty(): void
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => 'bar',
        ], 'baz');

        Assert::same($result, '          Foo: bar');
    }

    public function testRenderSingleItemArrayProperty(): void
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => ['hello'],
        ], 'baz');

        Assert::same($result, '          Foo: hello');
    }

    public function testRenderEmptyArrayProperty(): void
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => [],
        ], 'baz');

        Assert::same($result, '          Foo: []');
    }

    public function testRenderArrayProperty(): void
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => ['hello', 'world', 'baz'],
        ], 'baz');

        Assert::same($result, <<<'OUT'
          Foo: hello
               world
               baz
OUT);
    }

    #[BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
    }
}
