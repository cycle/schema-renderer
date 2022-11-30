<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\RelationSchema;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

class JTITest extends BaseTest
{
    public function testJTI(): void
    {
        $mermaid = new MermaidRenderer();

        $this->assertSame(<<<DIAGRAM

        classDiagram
        class employee {
            int id
            int employee_id
            string name_column
            int age
        }

        class role {
            int id
            int role_id
            int level
            string rank
            string _type
        }
        role --|> engineer : STI
        role --|> manager : STI
        employee --|> role : JTI
        class programator {
            string id
            int subrole_id
            string language
        }
        engineer --|> programator : JTI

        DIAGRAM, $mermaid->render($this->getSchema()));
    }

    public function getSchema(): array
    {
        return [
            'employee' => [
                SchemaInterface::ROLE => 'employee',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'employee',
                SchemaInterface::PRIMARY_KEY => ['id', 'employee_id'],
                SchemaInterface::COLUMNS => [
                    'id',
                    'employee_id',
                    'name' => 'name_column',
                    'age',
                ],
                SchemaInterface::TYPECAST => ['id' => 'int', 'employee_id' => 'int', 'age' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
            'manager' => [
                SchemaInterface::ROLE => 'role',
            ],
            'engineer' => [
                SchemaInterface::ROLE => 'role',
            ],
            'role' => [
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'role',
                SchemaInterface::PARENT => 'employee',
                SchemaInterface::CHILDREN => [
                    'engineer' => 'engineer',
                    'manager' => 'manager',
                ],
                SchemaInterface::PRIMARY_KEY => ['id', 'role_id'],
                SchemaInterface::COLUMNS => ['id', 'role_id', 'level', 'rank', '_type'],
                SchemaInterface::TYPECAST => ['id' => 'int', 'role_id' => 'int', 'level' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
            'programator' => [
                SchemaInterface::ROLE => 'programator',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'programator',
                SchemaInterface::PARENT => 'engineer',
                SchemaInterface::PARENT_KEY => ['role_id', 'id'],
                SchemaInterface::PRIMARY_KEY => ['second_id', 'subrole_id'],
                SchemaInterface::COLUMNS => ['second_id' => 'id', 'subrole_id', 'language'],
                SchemaInterface::TYPECAST => ['second_id' => 'int', 'subrole_id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
        ];
    }
}
