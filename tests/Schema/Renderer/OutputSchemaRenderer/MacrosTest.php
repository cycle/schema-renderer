<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\OutputSchemaRenderer;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\OutputSchemaRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\BaseTest;
use Cycle\Schema\Renderer\Tests\Fixture\User;

final class MacrosTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! $this->constantSupported('MACROS')) {
            $this->markTestSkipped('Only CycleORM v2 supports macros');
        }
    }

    public function testSchemaWithMacrosPropertyAsStringShouldBeRendered()
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
                SchemaInterface::MACROS => 'foo-macros',
            ],
        ];

        $schema = new Schema($schemaArray);
        $schemaArray = (new SchemaToArrayConverter())->convert($schema);
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT);

        $this->assertSame(
            <<<'OUTPUT'
[user] :: default.user
       Entity: Cycle\Schema\Renderer\Tests\Fixture\User
       Mapper: Cycle\ORM\Mapper\Mapper
  Primary key: id
       Fields:
               (property -> db.field -> typecast)
               0 -> id -> int
               1 -> email
               2 -> balance -> float
       Macros:
             foo-macros
    Relations: not defined


OUTPUT
            ,
            $renderer->render($schemaArray)
        );
    }

    public function testSchemaWithMacrosPropertyAsArrayShouldBeRendered()
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
                SchemaInterface::MACROS => [
                    'foo-macros',
                    'bar-macros',
                ],
            ],
        ];

        $schema = new Schema($schemaArray);
        $schemaArray = (new SchemaToArrayConverter())->convert($schema);
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT);

        $this->assertSame(
            <<<'OUTPUT'
[user] :: default.user
       Entity: Cycle\Schema\Renderer\Tests\Fixture\User
       Mapper: Cycle\ORM\Mapper\Mapper
  Primary key: id
       Fields:
               (property -> db.field -> typecast)
               0 -> id -> int
               1 -> email
               2 -> balance -> float
       Macros:
             foo-macros
             bar-macros
    Relations: not defined


OUTPUT
            ,
            $renderer->render($schemaArray)
        );
    }

    public function testSchemaWithMacrosPropertyAsAssocArrayShouldBeRendered()
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
                SchemaInterface::MACROS => [
                    ['foo-macros', []],
                    ['bar-macros', ['baz' => 'bar', 'baz1', 'baz2']],
                    ['baz-macros', 'baz'],
                    ['baz-macros', true],
                ],
            ],
        ];

        $schema = new Schema($schemaArray);
        $schemaArray = (new SchemaToArrayConverter())->convert($schema);
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT);

        $this->assertSame(
            <<<'OUTPUT'
[user] :: default.user
       Entity: Cycle\Schema\Renderer\Tests\Fixture\User
       Mapper: Cycle\ORM\Mapper\Mapper
  Primary key: id
       Fields:
               (property -> db.field -> typecast)
               0 -> id -> int
               1 -> email
               2 -> balance -> float
       Macros:
             foo-macros
             bar-macros
              - baz : bar
              - baz1
              - baz2
             baz-macros
              - baz
             baz-macros
array (
               0 => 'baz-macros',
               1 => true,
             )
    Relations: not defined


OUTPUT
            ,
            $renderer->render($schemaArray)
        );
    }
}
