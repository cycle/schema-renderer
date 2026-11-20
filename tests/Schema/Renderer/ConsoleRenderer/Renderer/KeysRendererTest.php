<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\KeysRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

class KeysRendererTest extends BaseTest
{
    private Formatter $formatter;

    #[\Testo\Lifecycle\BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
    }

    #[\Testo\Test]
    public function testRenderNullNotRequiredValue()
    {
        $renderer = new KeysRenderer(1, 'Foo', false);

        $result = $renderer->render($this->formatter, [1 => null], 'bar');

        \Testo\Assert::null($result);
    }

    #[\Testo\Test]
    public function testRenderNullRequiredValue()
    {
        $renderer = new KeysRenderer(1, 'Foo', true);

        $result = $renderer->render($this->formatter, [1 => null], 'bar');

        \Testo\Assert::same($result, '          Foo: not defined');
    }

    #[\Testo\Test]
    public function testRenderStringValue()
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => 'foo'], 'bar');

        \Testo\Assert::same($result, '          Foo: foo');
    }

    #[\Testo\Test]
    public function testRenderEmptyStringValue()
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => ''], 'bar');

        \Testo\Assert::same($result, '          Foo: not defined');
    }

    #[\Testo\Test]
    public function testRenderEmptyArrayValue()
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => []], 'bar');

        \Testo\Assert::same($result, '          Foo: not defined');
    }

    #[\Testo\Test]
    public function testRenderSingleItemArrayValue()
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => ['hello']], 'bar');

        \Testo\Assert::same($result, '          Foo: hello');
    }

    #[\Testo\Test]
    public function testRenderArrayValue()
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => ['hello', 'world']], 'bar');

        \Testo\Assert::same($result, '          Foo: hello, world');
    }
}
