<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\TitleRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

class TitleRendererTest extends BaseTest
{
    private Formatter $formatter;

    #[\Testo\Lifecycle\BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
    }

    #[\Testo\Test]
    public function testRenderWithDefinedProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => 'foo',
            SchemaInterface::TABLE => 'bar',
        ], 'foo');

        \Testo\Assert::same($result, '[foo] :: foo.bar');
    }

    #[\Testo\Test]
    public function testRenderWithNullDatabaseProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => null,
            SchemaInterface::TABLE => 'bar',
        ], 'foo');

        \Testo\Assert::same($result, '[foo] :: <undefined databse>.bar');
    }

    #[\Testo\Test]
    public function testRenderWithNulTableProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => 'foo',
            SchemaInterface::TABLE => null,
        ], 'foo');

        \Testo\Assert::same($result, '[foo] :: foo.<undefined table>');
    }

    #[\Testo\Test]
    public function testRenderWithoutDatabaseProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::TABLE => 'bar',
        ], 'foo');

        \Testo\Assert::same($result, '[foo] :: <undefined databse>.bar');
    }

    #[\Testo\Test]
    public function testRenderWithoutTableProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => 'foo',
        ], 'foo');

        \Testo\Assert::same($result, '[foo] :: foo.<undefined table>');
    }

    #[\Testo\Test]
    public function testRenderWithoutProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
        ], 'foo');

        \Testo\Assert::same($result, '[foo] :: <undefined databse>.<undefined table>');
    }
}
