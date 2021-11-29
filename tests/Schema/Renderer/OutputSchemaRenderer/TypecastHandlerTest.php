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

final class TypecastHandlerTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! $this->constantSupported('TYPECAST_HANDLER')) {
            $this->markTestSkipped('Only CycleORM v2 supports typecast handlers');
        }
    }

    public function testSchemaWithTypecastHandlerPropertyAsStringShouldBeRendered(): void
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
                SchemaInterface::TYPECAST_HANDLER => 'foo-handler',
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
     Typecast: foo-handler
    Relations: not defined


OUTPUT
            ,
            $renderer->render($schemaArray)
        );
    }

    public function testSchemaWithTypecastHandlerPropertyAsArrayShouldBeRendered(): void
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
                SchemaInterface::TYPECAST_HANDLER => [
                    'foo-handler',
                    'bat-handler',
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
     Typecast: foo-handler
               bat-handler
    Relations: not defined


OUTPUT
            ,
            $renderer->render($schemaArray)
        );
    }
}
