<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\Schema\Renderer\MermaidRenderer\Relation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class RelationTest extends BaseTest
{
    public function testValidRelation(): void
    {
        $rel = new Relation(
            'user',
            'files',
            'Foo comment',
            '-->',
            false
        );

        $this->assertSame('user --> files : Foo comment', (string) $rel);
    }

    public function testInvalidRelation(): void
    {
        $rel = new Relation(
            '&user%^&*',
            '@files&12~=_',
            '*& Foo comment -)',
            '-->',
            false
        );

        $this->assertSame('_user____ --> _files_12___ : *& Foo comment -)', (string) $rel);
    }

    public function testNullableRelation(): void
    {
        $rel = new Relation(
            'user',
            'files',
            'Foo comment',
            '-->',
            true
        );

        $this->assertSame('user --> "nullable" files : Foo comment', (string) $rel);
    }
}
