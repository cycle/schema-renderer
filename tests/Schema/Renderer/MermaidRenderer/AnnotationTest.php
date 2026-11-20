<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\MermaidRenderer;

use Cycle\Schema\Renderer\MermaidRenderer\Annotation;
use Cycle\Schema\Renderer\Tests\BaseTest;

final class AnnotationTest extends BaseTest
{
    #[\Testo\Test]
    public function testCasting(): void
    {
        $ann = new Annotation('some good annotation');

        \Testo\Assert::same((string)$ann, '<<some good annotation>>');
    }

    #[\Testo\Test]
    public function testNameWithSymbols(): void
    {
        $ann = new Annotation('>>some:good:annotation<<');

        \Testo\Assert::same((string)$ann, '<<&gt;&gt;some:good:annotation&lt;&lt;>>');
    }
}
