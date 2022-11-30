<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\RoleAliasCollection;
use Cycle\Schema\Renderer\Tests\BaseTest;

class RoleAliasCollectionTest extends BaseTest
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

        $this->assertSame('user', $collection->getAlias('user'));
        $this->assertSame('user_credentials_2', $collection->getAlias('user:credentials'));
        $this->assertSame('_foo_bar__1__', $collection->getAlias('?foo#bar.@1-='));
        $this->assertSame('foo_bar_buz', $collection->getAlias('foo bar buz'));
        $this->assertSame('undefined', $collection->getAlias('task'));
    }
}
