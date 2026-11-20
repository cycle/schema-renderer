<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\PhpFileRenderer\Exporter\Rendering;

use Cycle\ORM\Mapper\Mapper;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\Rendering\ValueRenderer;
use Testo\Assert;
use Testo\Test;

#[Test]
final class ValueRendererTest
{
    public function namespacedClassNameIsClassConstant(): void
    {
        Assert::same(ValueRenderer::render(Mapper::class), 'Cycle\ORM\Mapper\Mapper::class');
    }

    public function globalClassNameStaysString(): void
    {
        Assert::same(ValueRenderer::render('stdClass'), "'stdClass'");
    }

    public function nullIsRenderedAsKeyword(): void
    {
        Assert::same(ValueRenderer::render(null), 'null');
    }

    public function floatIsSerialized(): void
    {
        Assert::same(ValueRenderer::render(1.5), "unserialize('d:1.5;')");
    }
}
