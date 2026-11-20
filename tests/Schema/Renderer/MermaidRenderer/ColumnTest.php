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
    #[\Testo\Test]
    public function testTypecastArray(): void
    {
        $column = new Column([User::class, 'test'], 'foo');

        \Testo\Assert::same((string)$column, "[User::class, 'test'] foo");
    }

    #[\Testo\Test]
    public function testTypecastClosure(): void
    {
        $column = new Column(fn () => 'test', 'foo');

        \Testo\Assert::same((string)$column, 'Closure foo');
    }

    #[\Testo\Test]
    public function testTypecastDatetime(): void
    {
        $column = new Column('datetime', 'foo');

        \Testo\Assert::same((string)$column, 'datetime foo');
    }

    #[\Testo\Test]
    public function testTypecastObject(): void
    {
        $column = new Column(new PostContent(), 'foo');

        \Testo\Assert::same((string)$column, 'PostContent::class foo');
    }

    #[\Testo\Test]
    public function testTypecastClassString(): void
    {
        $column = new Column(PostImage::class, 'foo');

        \Testo\Assert::same((string)$column, 'PostImage::class foo');
    }

    #[\Testo\Test]
    public function testTypecastEntities(): void
    {
        $column = new Column("string 'quote'", 'foo');

        \Testo\Assert::same((string)$column, 'string &#039;quote&#039; foo');
    }
}
