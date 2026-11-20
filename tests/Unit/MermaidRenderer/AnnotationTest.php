<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer;

use Cycle\Schema\Renderer\MermaidRenderer\Annotation;
use Testo\Assert;
use Testo\Test;

#[Test]
final class AnnotationTest
{
    public function testCasting(): void
    {
        $ann = new Annotation('some good annotation');

        Assert::same((string) $ann, '<<some good annotation>>');
    }

    public function testNameWithSymbols(): void
    {
        $ann = new Annotation('>>some:good:annotation<<');

        Assert::same((string) $ann, '<<&gt;&gt;some:good:annotation&lt;&lt;>>');
    }
}
