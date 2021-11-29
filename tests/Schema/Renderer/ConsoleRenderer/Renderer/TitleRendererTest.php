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

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatter = new PlainFormatter();
    }

    public function testRenderWithDefinedProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => 'foo',
            SchemaInterface::TABLE => 'bar',
        ], 'foo');

        $this->assertSame('[foo] :: foo.bar', $result);
    }

    public function testRenderWithNullDatabaseProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => null,
            SchemaInterface::TABLE => 'bar',
        ], 'foo');

        $this->assertSame('[foo] :: <undefined databse>.bar', $result);
    }

    public function testRenderWithNulTableProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => 'foo',
            SchemaInterface::TABLE => null,
        ], 'foo');

        $this->assertSame('[foo] :: foo.<undefined table>', $result);
    }

    public function testRenderWithoutDatabaseProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::TABLE => 'bar',
        ], 'foo');

        $this->assertSame('[foo] :: <undefined databse>.bar', $result);
    }

    public function testRenderWithoutTableProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
            SchemaInterface::DATABASE => 'foo',
        ], 'foo');

        $this->assertSame('[foo] :: foo.<undefined table>', $result);
    }

    public function testRenderWithoutProperties()
    {
        $renderer = new TitleRenderer();

        $result = $renderer->render($this->formatter, [
        ], 'foo');

        $this->assertSame('[foo] :: <undefined databse>.<undefined table>', $result);
    }
}
