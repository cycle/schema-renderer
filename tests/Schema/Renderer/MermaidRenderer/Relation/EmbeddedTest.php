<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer\Relation;

use Cycle\ORM\Relation;
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
            foo_prop(Emb: user:credentials:foo)
            bar_prop(Emb: user:credentials:bar)
        }
        user -- user_credentials_foo : foo_prop
        user -- user_credentials_bar_1 : bar_prop
        class user_credentials_bar {
            int id
            string street
        }

        class user_credentials_foo {
            <<user:credentials:foo>>
            int id
            string street
        }

        class user_credentials_bar_1 {
            <<user:credentials:bar>>
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
                    'credential_foo' => [
                        Relation::TYPE => Relation::EMBEDDED,
                        Relation::TARGET => 'user:credentials:foo',
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [],
                    ],
                    'credential_bar' => [
                        Relation::TYPE => Relation::EMBEDDED,
                        Relation::TARGET => 'user:credentials:bar',
                        Relation::LOAD => Relation::LOAD_PROMISE,
                        Relation::SCHEMA => [],
                    ],
                ],
            ],
            'user_credentials_bar' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'street'],
                SchemaInterface::TYPECAST => ['id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
            'user:credentials:foo' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'street'],
                SchemaInterface::TYPECAST => ['id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
            'user:credentials:bar' => [
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
