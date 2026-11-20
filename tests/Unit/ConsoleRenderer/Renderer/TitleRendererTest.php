<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\ConsoleRenderer\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\TitleRenderer;
use Testo\Assert;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

#[Test]
final class TitleRendererTest
{
    private Formatter $formatter;

    public function testRenderWithDefinedProperties(): void
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => 'foo',
            SchemaInterface::TABLE => 'bar',
        ], 'foo');

        Assert::same($result, '[foo] :: foo.bar');
    }

    public function testRenderWithNullDatabaseProperties(): void
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => null,
            SchemaInterface::TABLE => 'bar',
        ], 'foo');

        Assert::same($result, '[foo] :: <undefined databse>.bar');
    }

    public function testRenderWithNulTableProperties(): void
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => 'foo',
            SchemaInterface::TABLE => null,
        ], 'foo');

        Assert::same($result, '[foo] :: foo.<undefined table>');
    }

    public function testRenderWithoutDatabaseProperties(): void
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::TABLE => 'bar',
        ], 'foo');

        Assert::same($result, '[foo] :: <undefined databse>.bar');
    }

    public function testRenderWithoutTableProperties(): void
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => 'foo',
        ], 'foo');

        Assert::same($result, '[foo] :: foo.<undefined table>');
    }

    public function testRenderWithoutProperties(): void
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
        ], 'foo');

        Assert::same($result, '[foo] :: <undefined databse>.<undefined table>');
    }

    #[BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
    }
}
