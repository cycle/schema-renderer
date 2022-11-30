<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Entity;

use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityTable;
use Cycle\Schema\Renderer\MermaidRenderer\Row;
use Cycle\Schema\Renderer\Tests\BaseTest;

class EntityTableTest extends BaseTest
{
    public function testToString(): void
    {
        $table = new EntityTable('table');

        $table->addRow(
            new Row('string', 'foo')
        );
        $table->addRow(
            new Row('float', 'bar')
        );

        $this->assertSame(<<<BLOCK
        class table {
            string foo
            float bar
        }
        BLOCK, (string)$table);
    }
}
