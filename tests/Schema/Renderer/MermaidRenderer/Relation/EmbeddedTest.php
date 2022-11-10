<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\ORM\Relation;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;
use Cycle\Schema\Renderer\Tests\BaseTest;

class EmbeddedTest extends BaseTest
{
    public function testEmbedded(): void
    {
        $mermaid = new MermaidRenderer();

        $this->assertSame(<<<SCHEMA

        classDiagram
        class user {
            int id
            int employee_id
            string name
            int age
            credentials(Emb: credential)
        }
        user -- credential : user&#58credentials
        class credential {
            int id
            string street
        }


        SCHEMA, $mermaid->render($this->getSchema()));
    }

    public function getSchema(): array
    {
        return [
            'user' => [
                SchemaInterface::ROLE => 'user',
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'employee_id', 'name', 'age'],
                SchemaInterface::TYPECAST => ['id' => 'int', 'employee_id' => 'int', 'age' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [
                    'credential' => [
                        Relation::TYPE => Relation::EMBEDDED,
                        Relation::TARGET => 'user:credentials',
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [],
                    ],
                ],
            ],
            'credential' => [
                SchemaInterface::ROLE => 'user:credentials',
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'street'],
                SchemaInterface::TYPECAST => ['id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
        ];
    }
}
