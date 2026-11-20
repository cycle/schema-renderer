<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\Schema\Renderer\MermaidRenderer\Relation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class RelationTest extends BaseTest
{
    #[\Testo\Test]
    public function testValidRelation(): void
    {
        $rel = new Relation(
            'user',
            'files',
            'Foo comment',
            '-->',
            false
        );

        \Testo\Assert::same((string) $rel, 'user --> files : Foo comment');
    }

    #[\Testo\Test]
    public function testInvalidRelation(): void
    {
        $rel = new Relation(
            '&user%^&*',
            '@files&12~=_',
            '*& Foo comment -)',
            '-->',
            false
        );

        \Testo\Assert::same((string) $rel, '_user____ --> _files_12___ : *& Foo comment -)');
    }

    #[\Testo\Test]
    public function testNullableRelation(): void
    {
        $rel = new Relation(
            'user',
            'files',
            'Foo comment',
            '-->',
            true
        );

        \Testo\Assert::same((string) $rel, 'user --> "nullable" files : Foo comment');
    }
}
