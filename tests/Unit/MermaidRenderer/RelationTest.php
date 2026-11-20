<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer;

use Cycle\Schema\Renderer\MermaidRenderer\Relation;
use Testo\Assert;
use Testo\Test;

#[Test]
final class RelationTest
{
    public function testValidRelation(): void
    {
        $rel = new Relation(
            'user',
            'files',
            'Foo comment',
            '-->',
            false,
        );

        Assert::same((string) $rel, 'user --> files : Foo comment');
    }

    public function testInvalidRelation(): void
    {
        $rel = new Relation(
            '&user%^&*',
            '@files&12~=_',
            '*& Foo comment -)',
            '-->',
            false,
        );

        Assert::same((string) $rel, '_user____ --> _files_12___ : *& Foo comment -)');
    }

    public function testNullableRelation(): void
    {
        $rel = new Relation(
            'user',
            'files',
            'Foo comment',
            '-->',
            true,
        );

        Assert::same((string) $rel, 'user --> "nullable" files : Foo comment');
    }
}
