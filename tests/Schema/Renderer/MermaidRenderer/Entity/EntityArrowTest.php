<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Entity;

use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityArrow;
use Cycle\Schema\Renderer\Tests\BaseTest;

class EntityArrowTest extends BaseTest
{
    public function testToString(): void
    {
        $arrow = new EntityArrow('table');

        $arrow->addArrow('foo', 'bar', true);
        $arrow->addArrow('bar', 'foo');

        $this->assertSame(<<<BLOCK
        table ||--0{ foo : bar
        table ||--|{ bar : foo
        BLOCK, $arrow->toString());
    }
}
