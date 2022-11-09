<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Entity;

use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityArrow;
use Cycle\Schema\Renderer\Tests\BaseTest;

class EntityArrowTest extends BaseTest
{
    public function testToString(): void
    {
        $arrow = new EntityArrow();

        $arrow->addArrow('table', 'foo', 'bar', '||--||');
        $arrow->addArrow('table','bar', 'foo', '||--o|');

        $this->assertSame(<<<BLOCK
        table ||--|| foo : bar
        table ||--o| bar : foo
        BLOCK, $arrow->toString());
    }
}
