<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer\RelationSchema;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;
use Testo\Assert;
use Testo\Test;

#[Test]
final class EmbeddedTest
{
    public function testEmbedded(): void
    {
        $mermaid = new MermaidRenderer();

        Assert::same($mermaid->render($this->getSchema()), <<<SCHEMA

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


        SCHEMA);
    }

    public function testEmbeddedRoleWithTwoParts(): void
    {
        $mermaid = new MermaidRenderer();

        Assert::same($mermaid->render([
            'user' => [
                SchemaInterface::ROLE => 'user',
                SchemaInterface::COLUMNS => ['id'],
                SchemaInterface::RELATIONS => [
                    'profile' => [
                        Relation::TYPE => Relation::EMBEDDED,
                        Relation::TARGET => 'user:profile',
                        Relation::SCHEMA => [],
                    ],
                ],
            ],
            'user:profile' => [
                SchemaInterface::COLUMNS => ['id'],
                SchemaInterface::RELATIONS => [],
            ],
        ]), <<<SCHEMA

        classDiagram
        class user {
            string id
            user_profile(Emb: user:profile)
        }
        user -- user_profile : user_profile
        class user_profile {
            <<user:profile>>
            string id
        }


        SCHEMA);
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
