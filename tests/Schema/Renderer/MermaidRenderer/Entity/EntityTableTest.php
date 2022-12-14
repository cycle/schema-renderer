<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Entity;

use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityTable;
use Cycle\Schema\Renderer\MermaidRenderer\Column;
use Cycle\Schema\Renderer\Tests\BaseTest;

class EntityTableTest extends BaseTest
{
    public function testToString(): void
    {
        $table = new EntityTable('table');

        $table->addColumn(
            new Column('string', 'foo')
        );
        $table->addColumn(
            new Column('float', 'bar')
        );

        $this->assertSame(<<<BLOCK
        class table {
            string foo
            float bar
        }
        BLOCK, (string)$table);
    }
}
