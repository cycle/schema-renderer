<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\PhpFileRenderer\Exporter\Rendering;

use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ExporterItem;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\Rendering\ArrayRenderer;
use Testo\Assert;
use Testo\Test;

#[Test]
final class ArrayRendererTest
{
    public function scalarListUpToMaxLineLengthIsInline(): void
    {
        $value = [\str_repeat('a', ExporterItem::MAX_LINE_LENGTH - 4)];

        $result = ArrayRenderer::render($value);

        Assert::same($result, "['" . $value[0] . "']");
    }

    public function scalarListOverMaxLineLengthIsBlock(): void
    {
        $value = [\str_repeat('a', ExporterItem::MAX_LINE_LENGTH - 3)];

        $result = ArrayRenderer::render($value);

        Assert::same($result, "[\n    '" . $value[0] . "',\n]");
    }

    public function listOfArraysIsBlock(): void
    {
        $result = ArrayRenderer::render([[1, 2], [3]]);

        Assert::same($result, <<<'PHP'
            [
                [1, 2],
                [3],
            ]
            PHP);
    }

    public function nestedArraysAreIndentedByLevel(): void
    {
        $result = ArrayRenderer::render(['a' => ['b' => ['c' => 1]]]);

        Assert::same($result, <<<'PHP'
            [
                'a' => [
                    'b' => [
                        'c' => 1,
                    ],
                ],
            ]
            PHP);
    }
}
