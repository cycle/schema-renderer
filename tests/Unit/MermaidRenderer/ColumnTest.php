<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer;

use Cycle\Schema\Renderer\MermaidRenderer\Column;
use Cycle\Schema\Renderer\Tests\Fixtures\Typecast\PostContent;
use Cycle\Schema\Renderer\Tests\Fixtures\Typecast\PostImage;
use Cycle\Schema\Renderer\Tests\Fixtures\User;
use Testo\Assert;
use Testo\Test;

#[Test]
final class ColumnTest
{
    public function testTypecastArray(): void
    {
        $column = new Column([User::class, 'test'], 'foo');

        Assert::same((string) $column, "[User::class, 'test'] foo");
    }

    public function testTypecastWithInvalidUtf8IsSubstituted(): void
    {
        $column = new Column("in\xFFt", 'foo');

        Assert::same((string) $column, "in\u{FFFD}t foo");
    }

    public function testTypecastClosure(): void
    {
        $column = new Column(static fn() => 'test', 'foo');

        Assert::same((string) $column, 'Closure foo');
    }

    public function testTypecastDatetime(): void
    {
        $column = new Column('datetime', 'foo');

        Assert::same((string) $column, 'datetime foo');
    }

    public function testTypecastObject(): void
    {
        $column = new Column(new PostContent(), 'foo');

        Assert::same((string) $column, 'PostContent::class foo');
    }

    public function testTypecastClassString(): void
    {
        $column = new Column(PostImage::class, 'foo');

        Assert::same((string) $column, 'PostImage::class foo');
    }

    public function testTypecastEntities(): void
    {
        $column = new Column("string 'quote'", 'foo');

        Assert::same((string) $column, 'string &#039;quote&#039; foo');
    }
}
