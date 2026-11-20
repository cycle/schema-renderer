<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\PhpFileRenderer\Exporter;

use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayItem;
use Testo\Assert;
use Testo\Test;

#[Test]
final class ArrayItemTest
{
    public function itemWithoutKeyIsIndented(): void
    {
        $item = (new ArrayItem('foo'))->setIndentLevel(2);

        Assert::same($item->toString(), "        'foo'");
    }
}
