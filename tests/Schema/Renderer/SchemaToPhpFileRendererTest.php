<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Relation;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\PhpFileRenderer\DefaultSchemaGenerator;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\SchemaToPhpFileRenderer;
use Cycle\Schema\Renderer\Tests\Fixtures\Tag;
use Cycle\Schema\Renderer\Tests\Fixtures\TagContext;
use Cycle\Schema\Renderer\Tests\Fixtures\User;
use PHPUnit\Framework\TestCase;

class SchemaToPhpFileRendererTest extends TestCase
{
    private array $schema;

    protected function setUp(): void
    {
        parent::setUp();

        $schema = new Schema([
            User::class => [
                SchemaInterface::ROLE => 'user',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'user',
                SchemaInterface::PRIMARY_KEY => 'id',
                SchemaInterface::COLUMNS => ['id', 'email', 'balance'],
                SchemaInterface::TYPECAST => ['id' => 'int', 'balance' => 'float'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [
                    'tags' => [
                        Relation::TYPE => Relation::MANY_TO_MANY,
                        Relation::TARGET => Tag::class,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::THROUGH_ENTITY => 'tag_context',
                            Relation::INNER_KEY => 'id',
                            Relation::OUTER_KEY => 'id',
                            Relation::THROUGH_INNER_KEY => 'user_id',
                            Relation::THROUGH_OUTER_KEY => 'tag_id',
                        ],
                    ],
                    'tag' => [
                        Relation::TYPE => Relation::BELONGS_TO,
                        Relation::TARGET => Tag::class,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::INNER_KEY => 'tag_id',
                            Relation::OUTER_KEY => 'id',
                        ],
                    ],
                ]
            ],
            Tag::class => [
                SchemaInterface::ROLE => 'tag',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'tag',
                SchemaInterface::PRIMARY_KEY => ['id', 'name'],
                SchemaInterface::COLUMNS => ['id', 'name'],
                SchemaInterface::TYPECAST => ['id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [
                    'user' => [
                        Relation::TYPE => Relation::BELONGS_TO,
                        Relation::TARGET => User::class,
                        Relation::SCHEMA => [
                            Relation::CASCADE => true,
                            Relation::INNER_KEY => 'user_id',
                            Relation::OUTER_KEY => 'id',
                        ],
                    ]
                ]
            ],
            TagContext::class => [
                SchemaInterface::ROLE => 'tag_context',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'tag_user_map',
                SchemaInterface::COLUMNS => [],
                SchemaInterface::TYPECAST => ['id' => 'int', 'user_id' => 'int', 'tag_id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => []
            ]
        ]);

        $this->schema = (new SchemaToArrayConverter())->convert($schema);
    }

    public function testRenderSchemaToPhpCode(): void
    {
        $renderer = new SchemaToPhpFileRenderer($this->schema, new DefaultSchemaGenerator());

        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/generated_schema.php'),
            $renderer->render()
        );
    }

    public function testRenderEmptySchemaToPhpCode(): void
    {
        $renderer = new SchemaToPhpFileRenderer([], new DefaultSchemaGenerator());

        $this->assertSame(
            <<<EOL
<?php

declare(strict_types=1);

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;

return [

];
EOL
            ,
            $renderer->render()
        );
    }
}
