<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Relation;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\PhpSchemaRenderer;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\Fixtures\Tag;
use Cycle\Schema\Renderer\Tests\Fixtures\TagContext;
use Cycle\Schema\Renderer\Tests\Fixtures\User;
use Testo\Assert;
use Testo\Lifecycle\BeforeTest;
use Testo\Test;

#[Test]
final class PhpSchemaRendererTest
{
    private array $schema;

    # First in the class: RelationBlock builds its key map once per process, so only the first rendered
    # relation covers that code for Infection.
    public function testRenderRelationWithCompositeKeysAndCustomOption(): void
    {
        $renderer = new PhpSchemaRenderer();

        Assert::same($renderer->render([
            'user' => [
                SchemaInterface::RELATIONS => [
                    'tag' => [
                        Relation::TYPE => Relation::BELONGS_TO,
                        Relation::TARGET => 'tag',
                        Relation::SCHEMA => [
                            Relation::INNER_KEY => ['tag_id', 'tag_name'],
                            Relation::OUTER_KEY => ['id', 'name'],
                            10 => 'custom',
                        ],
                    ],
                ],
            ],
        ]), <<<PHP
                <?php

                declare(strict_types=1);

                use Cycle\ORM\Relation;
                use Cycle\ORM\SchemaInterface as Schema;

                return [
                    'user' => [
                        Schema::RELATIONS => [
                            'tag' => [
                                Relation::TYPE => Relation::BELONGS_TO,
                                Relation::TARGET => 'tag',
                                Relation::SCHEMA => [
                                    Relation::INNER_KEY => ['tag_id', 'tag_name'],
                                    Relation::OUTER_KEY => ['id', 'name'],
                                    '10' => 'custom',
                                ],
                            ],
                        ],
                    ],
                ];

                PHP);
    }

    public function testRenderSchemaToPhpCode(): void
    {
        $renderer = new PhpSchemaRenderer();
        $expected = \file_get_contents(__DIR__ . '/../Fixtures/generated_schema_v2.stub.php');

        Assert::same($renderer->render($this->schema), $expected);
    }

    public function testRenderEmptySchemaToPhpCode(): void
    {
        $renderer = new PhpSchemaRenderer();

        Assert::same($renderer->render([]), <<<PHP
                <?php

                declare(strict_types=1);

                use Cycle\ORM\Relation;
                use Cycle\ORM\SchemaInterface as Schema;

                return [];

                PHP);
    }

    public function testRenderSchemaWithCustomPropertyToPhpCode(): void
    {
        $renderer = new PhpSchemaRenderer();

        Assert::same($renderer->render([
            TagContext::class => [
                SchemaInterface::ROLE => 'tag_context',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'tag_user_map',
                SchemaInterface::COLUMNS => [],
                SchemaInterface::TYPECAST => ['id' => 'int', 'user_id' => 'int', 'tag_id' => 'int'],
                SchemaInterface::SCHEMA => [],
                100 => 'Hello world',
                'hello' => 'world',
            ],
        ]), <<<PHP
                <?php

                declare(strict_types=1);

                use Cycle\ORM\Relation;
                use Cycle\ORM\SchemaInterface as Schema;

                return [
                    'Cycle\Schema\Renderer\Tests\Fixtures\TagContext' => [
                        Schema::ROLE => 'tag_context',
                        Schema::MAPPER => Cycle\ORM\Mapper\Mapper::class,
                        Schema::DATABASE => 'default',
                        Schema::TABLE => 'tag_user_map',
                        Schema::COLUMNS => [],
                        Schema::TYPECAST => [
                            'id' => 'int',
                            'user_id' => 'int',
                            'tag_id' => 'int',
                        ],
                        Schema::SCHEMA => [],
                        '100' => 'Hello world',
                        'hello' => 'world',
                    ],
                ];

                PHP);
    }

    #[BeforeTest]
    protected function setUp(): void
    {
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
                ],
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
                    ],
                ],
            ],
            TagContext::class => [
                SchemaInterface::ROLE => 'tag_context',
                SchemaInterface::MAPPER => Mapper::class,
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'tag_user_map',
                SchemaInterface::COLUMNS => [],
                SchemaInterface::TYPECAST => ['id' => 'int', 'user_id' => 'int', 'tag_id' => 'int'],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
            ],
        ]);

        $this->schema = (new SchemaToArrayConverter())->convert($schema);
    }
}
