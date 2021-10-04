<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Exporter\Rendering;

interface Indentable
{
    public const INDENT = '    ';

    public function setIndentLevel(int $indentLevel = 0): self;
}
