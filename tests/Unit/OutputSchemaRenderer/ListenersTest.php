<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\OutputSchemaRenderer;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\OutputSchemaRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\Fixtures\User;
use Testo\Assert;
use Testo\Test;

#[Test]
final class ListenersTest
{
    public function testSchemaWithListenerPropertyAsStringShouldBeRendered(): void
    {
        $schemaArray = [
            User::class => [
                SchemaInterface::ROLE => 'user',
                SchemaInterface::SOURCE => 'test',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'email', 'balance'],
                SchemaInterface::TYPECAST => ['id' => 'int', 'balance' => 'float'],
                SchemaInterface::LISTENERS => 'foo-listener',
            ],
        ];

        $schema = new Schema($schemaArray);
        $schemaArray = (new SchemaToArrayConverter())->convert($schema);
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT);

        Assert::same($renderer->render($schemaArray), <<<'OUTPUT'
[user] :: default.user
       Entity: Cycle\Schema\Renderer\Tests\Fixtures\User
       Mapper: Cycle\ORM\Mapper\Mapper
  Primary key: id
       Fields:
               (property -> db.field -> typecast)
               0 -> id -> int
               1 -> email
               2 -> balance -> float
    Listeners:
             foo-listener
    Relations: not defined


OUTPUT);
    }

    public function testSchemaWithListenerPropertyAsArrayShouldBeRendered(): void
    {
        $schemaArray = [
            User::class => [
                SchemaInterface::ROLE => 'user',
                SchemaInterface::SOURCE => 'test',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'email', 'balance'],
                SchemaInterface::TYPECAST => ['id' => 'int', 'balance' => 'float'],
                SchemaInterface::LISTENERS => [
                    'foo-listener',
                    'bar-listener',
                ],
            ],
        ];

        $schema = new Schema($schemaArray);
        $schemaArray = (new SchemaToArrayConverter())->convert($schema);
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT);

        Assert::same($renderer->render($schemaArray), <<<'OUTPUT'
[user] :: default.user
       Entity: Cycle\Schema\Renderer\Tests\Fixtures\User
       Mapper: Cycle\ORM\Mapper\Mapper
  Primary key: id
       Fields:
               (property -> db.field -> typecast)
               0 -> id -> int
               1 -> email
               2 -> balance -> float
    Listeners:
             foo-listener
             bar-listener
    Relations: not defined


OUTPUT);
    }

    public function testSchemaWithListenerPropertyAsAssocArrayShouldBeRendered(): void
    {
        $schemaArray = [
            User::class => [
                SchemaInterface::ROLE => 'user',
                SchemaInterface::SOURCE => 'test',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'email', 'balance'],
                SchemaInterface::TYPECAST => ['id' => 'int', 'balance' => 'float'],
                SchemaInterface::LISTENERS => [
                    ['foo-listener', []],
                    ['bar-listener', ['baz' => 'bar', 'baz1', 'baz2']],
                    ['baz-listener', 'baz'],
                    ['baz-listener', true],
                ],
            ],
        ];

        $schema = new Schema($schemaArray);
        $schemaArray = (new SchemaToArrayConverter())->convert($schema);
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT);

        Assert::same($renderer->render($schemaArray), <<<'OUTPUT'
[user] :: default.user
       Entity: Cycle\Schema\Renderer\Tests\Fixtures\User
       Mapper: Cycle\ORM\Mapper\Mapper
  Primary key: id
       Fields:
               (property -> db.field -> typecast)
               0 -> id -> int
               1 -> email
               2 -> balance -> float
    Listeners:
             foo-listener
             bar-listener
              - baz : bar
              - baz1
              - baz2
             baz-listener
              - baz
             baz-listener
array (
               0 => 'baz-listener',
               1 => true,
             )
    Relations: not defined


OUTPUT);
    }
}
