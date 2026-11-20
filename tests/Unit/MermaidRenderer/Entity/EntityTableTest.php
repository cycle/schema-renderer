<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\Entity;

use Cycle\Schema\Renderer\MermaidRenderer\Column;
use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityTable;
use Testo\Assert;
use Testo\Test;

#[Test]
final class EntityTableTest
{
    public function testToString(): void
    {
        $table = new EntityTable('table');

        $table->addColumn(
            new Column('string', 'foo'),
        );
        $table->addColumn(
            new Column('float', 'bar'),
        );

        Assert::same((string) $table, <<<BLOCK
        class table {
            string foo
            float bar
        }
        BLOCK);
    }
}
