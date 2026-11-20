<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\OutputSchemaRenderer;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\OutputSchemaRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\Fixtures\Guest;
use Cycle\Schema\Renderer\Tests\Fixtures\User;
use Testo\Assert;
use Testo\Test;

#[Test]
final class JoinedTableInheritanceTest
{
    public function testSchemaWithParentPropertyShouldBeRendered(): void
    {
        $schemaArray = [
            Guest::class => [
                SchemaInterface::ROLE => 'guest',
                SchemaInterface::SOURCE => 'test',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'guest',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'email', 'balance'],
                SchemaInterface::TYPECAST => ['id' => 'int', 'balance' => 'float'],
                SchemaInterface::PARENT => User::class,
                SchemaInterface::PARENT_KEY => 'id',
            ],
        ];

        $schema = new Schema($schemaArray);
        $schemaArray = (new SchemaToArrayConverter())->convert($schema);
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT);

        Assert::same($renderer->render($schemaArray), <<<'OUTPUT'
[guest] :: default.guest
       Entity: Cycle\Schema\Renderer\Tests\Fixtures\Guest
       Mapper: Cycle\ORM\Mapper\Mapper
  Primary key: id
       Parent: Cycle\Schema\Renderer\Tests\Fixtures\User
   Parent key: id
       Fields:
               (property -> db.field -> typecast)
               0 -> id -> int
               1 -> email
               2 -> balance -> float
    Relations: not defined


OUTPUT);
    }
}
