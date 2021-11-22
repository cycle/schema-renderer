<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\PropertyRenderer;
use PHPUnit\Framework\TestCase;

class PropertyRendererTest extends TestCase
{
    private Formatter $formatter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatter = new PlainFormatter();
    }

    public function testRenderNotExistsProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [], 'baz');

        $this->assertNull($result);
    }

    public function testRenderNotExistsRequiredProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo', true);

        $result = $renderer->render($this->formatter, [], 'baz');

        $this->assertSame('          Foo: not defined', $result);
    }

    public function testRenderNullNotRequiredProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => null,
        ], 'baz');

        $this->assertNull($result);
    }

    public function testRenderNullRequiredProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo', true);

        $result = $renderer->render($this->formatter, [
            1 => null,
        ], 'baz');

        $this->assertSame('          Foo: not defined', $result);
    }

    public function testRenderStringProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => 'bar',
        ], 'baz');

        $this->assertSame('          Foo: bar', $result);
    }

    public function testRenderSingleItemArrayProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => ['hello'],
        ], 'baz');

        $this->assertSame('          Foo: hello', $result);
    }

    public function testRenderEmptyArrayProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => [],
        ], 'baz');

        $this->assertSame('          Foo: []', $result);
    }

    public function testRenderArrayProperty()
    {
        $renderer = new PropertyRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [
            1 => ['hello', 'world', 'baz'],
        ], 'baz');

        $this->assertSame(
            <<<'OUT'
          Foo: hello
               world
               baz
OUT
            ,
            $result
        );
    }
}
