<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\Schema\Renderer\MermaidRenderer\Column;
use Cycle\Schema\Renderer\Tests\BaseTest;
use Cycle\Schema\Renderer\Tests\Fixture\Typecast\PostContent;
use Cycle\Schema\Renderer\Tests\Fixture\Typecast\PostImage;
use Cycle\Schema\Renderer\Tests\Fixture\User;

class ColumnTest extends BaseTest
{
    public function testTypecastArray(): void
    {
        $column = new Column([User::class, 'test'], 'foo');

        $this->assertSame("[User::class, 'test'] foo", (string)$column);
    }

    public function testTypecastClosure(): void
    {
        $column = new Column(fn () => 'test', 'foo');

        $this->assertSame('Closure foo', (string)$column);
    }

    public function testTypecastDatetime(): void
    {
        $column = new Column('datetime', 'foo');

        $this->assertSame('datetime foo', (string)$column);
    }

    public function testTypecastObject(): void
    {
        $column = new Column(new PostContent(), 'foo');

        $this->assertSame('PostContent::class foo', (string)$column);
    }

    public function testTypecastClassString(): void
    {
        $column = new Column(PostImage::class, 'foo');

        $this->assertSame('PostImage::class foo', (string)$column);
    }

    public function testTypecastEntities(): void
    {
        $column = new Column("string 'quote'", 'foo');

        $this->assertSame('string &#039;quote&#039; foo', (string)$column);
    }
}
