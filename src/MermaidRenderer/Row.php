<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

final class Row implements \Stringable
{
    private string $type;
    private string $column;

    public function __construct(string $type, string $column)
    {
        $this->type = $type;
        $this->column = $column;
    }

    public function __toString(): string
    {
        return "$this->type $this->column";
    }
}
