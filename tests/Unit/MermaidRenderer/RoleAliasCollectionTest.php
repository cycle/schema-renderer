<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\RoleAliasCollection;
use Testo\Assert;
use Testo\Test;

#[Test]
final class RoleAliasCollectionTest
{
    public function testGetAlias(): void
    {
        $collection = new RoleAliasCollection([
            'user' => [
                SchemaInterface::ROLE => 'user',
            ],
            'user:credentials' => [
                SchemaInterface::ROLE => 'user:credentials',
            ],
            'user_credentials' => [
                SchemaInterface::ROLE => 'user_credentials',
            ],
            'user_credentials_1' => [
                SchemaInterface::ROLE => 'user_credentials_1',
            ],
            'foo bar buz' => [
                SchemaInterface::ROLE => 'foo bar buz',
            ],
            '?foo#bar.@1-=' => [
                SchemaInterface::ROLE => 'foo#bar.@1-=',
            ],
        ]);

        Assert::same($collection->getAlias('user'), 'user');
        Assert::same($collection->getAlias('user:credentials'), 'user_credentials_2');
        Assert::same($collection->getAlias('?foo#bar.@1-='), '_foo_bar__1__');
        Assert::same($collection->getAlias('foo bar buz'), 'foo_bar_buz');
        Assert::same($collection->getAlias('task'), 'undefined');
    }
}
