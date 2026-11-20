<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\KeysRenderer;
use Testo\Assert;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

#[Test]
final class KeysRendererTest
{
    private Formatter $formatter;

    public function testRenderNullNotRequiredValue(): void
    {
        $renderer = new KeysRenderer(1, 'Foo', false);

        $result = $renderer->render($this->formatter, [1 => null], 'bar');

        Assert::null($result);
    }

    public function testRenderNullRequiredValue(): void
    {
        $renderer = new KeysRenderer(1, 'Foo', true);

        $result = $renderer->render($this->formatter, [1 => null], 'bar');

        Assert::same($result, '          Foo: not defined');
    }

    public function testRenderStringValue(): void
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => 'foo'], 'bar');

        Assert::same($result, '          Foo: foo');
    }

    public function testRenderEmptyStringValue(): void
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => ''], 'bar');

        Assert::same($result, '          Foo: not defined');
    }

    public function testRenderEmptyArrayValue(): void
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => []], 'bar');

        Assert::same($result, '          Foo: not defined');
    }

    public function testRenderSingleItemArrayValue(): void
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => ['hello']], 'bar');

        Assert::same($result, '          Foo: hello');
    }

    public function testRenderArrayValue(): void
    {
        $renderer = new KeysRenderer(1, 'Foo');

        $result = $renderer->render($this->formatter, [1 => ['hello', 'world']], 'bar');

        Assert::same($result, '          Foo: hello, world');
    }

    #[BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
    }
}
