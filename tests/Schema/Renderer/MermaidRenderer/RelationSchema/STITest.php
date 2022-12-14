<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\RelationSchema;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

class STITest extends BaseTest
{
    public function testSTI(): void
    {
        $mermaid = new MermaidRenderer();

        $this->assertSame(<<<DIAGRAM

        classDiagram
        class employee_table {
            int id
            string _type
            string name
            string email
            int age
        }

        class manager {
            int id
            string name
            string email
            int age
        }
        manager --|> employee_table : STI

        DIAGRAM, $mermaid->render($this->getSchema()));
    }

    public function getSchema(): array
    {
        return [
            'employee_table' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'employee_table',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', '_type', 'name', 'email', 'age'],
                SchemaInterface::TYPECAST => ['id' => 'int', 'age' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
            'manager' => [
                SchemaInterface::TABLE => 'manager',
                SchemaInterface::CHILDREN => [
                    'employee_table' => 'employee_table',
                ],
                SchemaInterface::COLUMNS => ['id', 'name', 'email', 'age'],
                SchemaInterface::TYPECAST => ['id' => 'int', 'age' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
        ];
    }
}
