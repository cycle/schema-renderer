<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\SchemaToArrayConverter;

use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConstantsInterface;
use Cycle\Schema\Renderer\SchemaToArrayConverter;
use Cycle\Schema\Renderer\Tests\Fixtures\SchemaWithoutToArray;
use Testo\Assert;
use Testo\Test;

#[Test]
final class CustomConstantsTest
{
    public function testOnlyGivenPropertiesAreConverted(): void
    {
        $constants = new class implements ConstantsInterface {
            public function all(): array
            {
                return ['SOURCE' => SchemaInterface::SOURCE, 'TABLE' => SchemaInterface::TABLE];
            }
        };
        $schema = new SchemaWithoutToArray(new Schema([
            'user' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'users',
                100 => 'custom',
            ],
            'tag' => [
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'tags',
            ],
        ]));

        $result = (new SchemaToArrayConverter($constants))->convert($schema, [100]);

        Assert::same($result, [
            'user' => [
                SchemaInterface::TABLE => 'users',
                100 => 'custom',
            ],
            'tag' => [
                SchemaInterface::TABLE => 'tags',
            ],
        ]);
    }
}
