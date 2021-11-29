<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\ConsoleRenderer\Renderer;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\CustomPropertiesRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;
use Cycle\Schema\Renderer\Tests\Fixture\User;

class CustomPropertiesRendererTest extends BaseTest
{
    private Formatter $formatter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatter = new PlainFormatter();
    }

    public function testCustomPropertiesShouldBeRendered()
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
            'bool' => false
        ], 'foo');

        $this->assertSame(
            <<<'OUTPUT'
 Custom props:
             int: 3
             string: Hello world
             array: array (
               'foo' => 'bar',
             )
             object: Cycle\Schema\Renderer\Tests\Fixture\User::__set_state(array(
             ))
             bool: false
OUTPUT
            ,
            $result
        );
    }
}
