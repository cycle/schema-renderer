<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\OutputSchemaRenderer;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\OutputSchemaRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\BaseTest;
use Cycle\Schema\Renderer\Tests\Fixture\Admin;
use Cycle\Schema\Renderer\Tests\Fixture\Guest;
use Cycle\Schema\Renderer\Tests\Fixture\User;

final class SingleTableInheritanceTest extends BaseTest
{
    #[\Testo\Test]
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

        \Testo\Assert::same($renderer->render($schemaArray), <<<'OUTPUT'
[Cycle\Schema\Renderer\Tests\Fixture\User] :: default.user
         Role: user
       Mapper: Cycle\ORM\Mapper\Mapper
  Primary key: id
     Children: Cycle\Schema\Renderer\Tests\Fixture\Guest
               Cycle\Schema\Renderer\Tests\Fixture\Admin
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
//       Entity: Cycle\Schema\Renderer\Tests\Fixture\User
//       Mapper: Cycle\ORM\Mapper\Mapper
//  Primary key: id
//     Children: Cycle\Schema\Renderer\Tests\Fixture\Guest
//               Cycle\Schema\Renderer\Tests\Fixture\Admin
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

    #[\Testo\Test]
    public function testSchemaWithChildrenAndDiscriminatorPropertiesShouldBeRendered(): void
    {
        if (! $this->constantSupported('CHILDREN', 'DISCRIMINATOR')) {
            throw new \Testo\Core\Exception\SkipTest('Only CycleORM v2 supports STI with discriminator');
        }

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

        \Testo\Assert::same($renderer->render($schemaArray), <<<'OUTPUT'
[user] :: default.user
       Entity: Cycle\Schema\Renderer\Tests\Fixture\User
       Mapper: Cycle\ORM\Mapper\Mapper
  Primary key: id
     Children: Cycle\Schema\Renderer\Tests\Fixture\Guest
               Cycle\Schema\Renderer\Tests\Fixture\Admin
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
