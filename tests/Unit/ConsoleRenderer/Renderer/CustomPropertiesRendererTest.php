<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\CustomPropertiesRenderer;
use Cycle\Schema\Renderer\Tests\Fixtures\User;
use Testo\Assert;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

#[Test]
final class CustomPropertiesRendererTest
{
    private Formatter $formatter;

    public function testCustomPropertiesShouldBeRendered(): void
    {
        $renderer = new CustomPropertiesRenderer(['bar', 'baz']);

        $object = new User();

        $result = $renderer->render($this->formatter, [
            'bar' => 1,
            'baz' => 2,
            'int' => 3,
            'string' => 'Hello world',
            'array' => ['foo' => 'bar'],
            'object' => $object,
            'bool' => false,
        ], 'foo');

        Assert::same($result, <<<'OUTPUT'
 Custom props:
             int: 3
             string: Hello world
             array: array (
               'foo' => 'bar',
             )
             object: \Cycle\Schema\Renderer\Tests\Fixtures\User::__set_state(array(
             ))
             bool: false
OUTPUT);
    }

    #[BeforeTest]
    protected function setUp(): void
    {
        $this->formatter = new PlainFormatter();
    }
}
