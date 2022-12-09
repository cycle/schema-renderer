<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\Schema\Renderer\MermaidRenderer\Annotation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class AnnotationTest extends BaseTest
{
    public function testCasting(): void
    {
        $ann = new Annotation('some good annotation');

        $this->assertSame('<<some good annotation>>', (string)$ann);
    }

    public function testNameWithSymbols(): void
    {
        $ann = new Annotation('>>some good annotation<<');

        $this->assertSame(
            '<<&gt;&gt;some good annotation&lt;&lt;>>',
            (string)$ann
        );
    }
}
