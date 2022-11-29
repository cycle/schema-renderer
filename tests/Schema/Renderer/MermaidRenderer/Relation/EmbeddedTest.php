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
            user_credentials_3(Emb: credential)
        }
        user -- user_credentials_3 : user_credentials_3
        class user_credentials_3 {
            int id
            string street
        }

        class user_credentials {
            int id
            string street
        }

        class user_credentials_1 {
            int id
            string street
        }

        class user_credentials_2 {
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
            'user:credentials' => [
                SchemaInterface::ROLE => 'user:credentials',
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'street'],
                SchemaInterface::TYPECAST => ['id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
            'user_credentials' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'street'],
                SchemaInterface::TYPECAST => ['id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
            'user_credentials_1' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'street'],
                SchemaInterface::TYPECAST => ['id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
            'user_credentials_2' => [
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
