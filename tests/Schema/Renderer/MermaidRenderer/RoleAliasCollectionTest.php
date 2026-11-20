<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\RoleAliasCollection;
use Cycle\Schema\Renderer\Tests\BaseTest;

class RoleAliasCollectionTest extends BaseTest
{
    #[\Testo\Test]
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
                SchemaInterface::ROLE => 'user_credentials'
            ],
            'user_credentials_1' => [
                SchemaInterface::ROLE => 'user_credentials_1'
            ],
            'foo bar buz' => [
                SchemaInterface::ROLE => 'foo bar buz'
            ],
            '?foo#bar.@1-=' => [
                SchemaInterface::ROLE => 'foo#bar.@1-='
            ],
        ]);

        \Testo\Assert::same($collection->getAlias('user'), 'user');
        \Testo\Assert::same($collection->getAlias('user:credentials'), 'user_credentials_2');
        \Testo\Assert::same($collection->getAlias('?foo#bar.@1-='), '_foo_bar__1__');
        \Testo\Assert::same($collection->getAlias('foo bar buz'), 'foo_bar_buz');
        \Testo\Assert::same($collection->getAlias('task'), 'undefined');
    }
}
