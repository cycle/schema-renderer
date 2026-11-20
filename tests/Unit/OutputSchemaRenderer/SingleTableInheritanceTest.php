<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\OutputSchemaRenderer;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\OutputSchemaRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\Fixtures\Admin;
use Cycle\Schema\Renderer\Tests\Fixtures\Guest;
use Cycle\Schema\Renderer\Tests\Fixtures\User;
use Testo\Assert;
use Testo\Test;

#[Test]
final class SingleTableInheritanceTest
{
    public function testSchemaWithChildrenPropertyShouldBeRendered(): void
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
                SchemaInterface::CHILDREN => [
                    'guest' => Guest::class,
                    'admin' => Admin::class,
                ],
            ],
        ];

        // TODO need fix
        //$schema = new Schema($schemaArray);
        //$schemaArray = (new SchemaToArrayConverter())->convert($schema);
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT);

        Assert::same($renderer->render($schemaArray), <<<'OUTPUT'
[Cycle\Schema\Renderer\Tests\Fixtures\User] :: default.user
         Role: user
       Mapper: Cycle\ORM\Mapper\Mapper
  Primary key: id
     Children: Cycle\Schema\Renderer\Tests\Fixtures\Guest
               Cycle\Schema\Renderer\Tests\Fixtures\Admin
       Fields:
               (property -> db.field -> typecast)
               0 -> id -> int
               1 -> email
               2 -> balance -> float
    Relations: not defined


OUTPUT);

        // TODO related to todo above
        //        $this->assertSame(
        //            <<<'OUTPUT'
        //[user] :: default.user
        //       Entity: Cycle\Schema\Renderer\Tests\Fixtures\User
        //       Mapper: Cycle\ORM\Mapper\Mapper
        //  Primary key: id
        //     Children: Cycle\Schema\Renderer\Tests\Fixtures\Guest
        //               Cycle\Schema\Renderer\Tests\Fixtures\Admin
        //       Fields:
        //               (property -> db.field -> typecast)
        //               0 -> id -> int
        //               1 -> email
        //               2 -> balance -> float
        //    Relations: not defined
        //
        //
        //OUTPUT
        //            ,
        //            $renderer->render($schemaArray)
        //        );
    }

    public function testSchemaWithChildrenAndDiscriminatorPropertiesShouldBeRendered(): void
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
                SchemaInterface::CHILDREN => [
                    'guest' => Guest::class,
                    'admin' => Admin::class,
                ],
                SchemaInterface::DISCRIMINATOR => 'type',
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
     Children: Cycle\Schema\Renderer\Tests\Fixtures\Guest
               Cycle\Schema\Renderer\Tests\Fixtures\Admin
Discriminator: type
       Fields:
               (property -> db.field -> typecast)
               0 -> id -> int
               1 -> email
               2 -> balance -> float
    Relations: not defined


OUTPUT);
    }
}
