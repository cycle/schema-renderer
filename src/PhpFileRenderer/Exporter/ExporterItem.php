<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Exporter;

interface ExporterItem
{
    public const MAX_LINE_LENGTH = 120;

    public function toString(): string;
}
