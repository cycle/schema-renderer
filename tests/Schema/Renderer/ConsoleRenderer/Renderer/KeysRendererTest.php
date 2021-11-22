<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\KeysRenderer;
use PHPUnit\Framework\TestCase;

class KeysRendererTest extends TestCase
{
    private Formatter $formatter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatter = new PlainFormatter();
    }

    public function testRenderNullNotRequiredValue()
    {
        $renderer = new KeysRenderer(1, 'Foo', false);

        $result = $renderer->render($this->formatter, [1 => null], 'bar');

        $this->assertNull($result);
    }

    public function testRenderNullRequiredValue()
    {
        $renderer = new KeysRenderer(1, 'Foo', true);

        $result = $renderer->render($this->formatter, [1 => null], 'bar');

        $this->assertSame('          Foo: not defined', $result);
    }

    public function testRenderStringValue()
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => 'foo'], 'bar');

        $this->assertSame('          Foo: foo', $result);
    }

    public function testRenderEmptyStringValue()
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => ''], 'bar');

        $this->assertSame('          Foo: not defined', $result);
    }

    public function testRenderEmptyArrayValue()
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => []], 'bar');

        $this->assertSame('          Foo: not defined', $result);
    }

    public function testRenderSingleItemArrayValue()
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => ['hello']], 'bar');

        $this->assertSame('          Foo: hello', $result);
    }

    public function testRenderArrayValue()
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => ['hello', 'world']], 'bar');

        $this->assertSame('          Foo: hello, world', $result);
    }
}
