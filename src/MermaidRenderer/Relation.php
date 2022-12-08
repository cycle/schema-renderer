<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

class Relation implements Stringable
{
    use StringFormatter;

    private const BLOCK = '%s %s %s : %s';

    private string $parent;
    private string $children;
    private string $comment;
    private string $arrow;
    private bool $isNullable;

    public function __construct(
        string $parent,
        string $children,
        string $comment,
        string $arrow,
        bool $isNullable
    ) {
        $this->parent = $this->resolveString($parent);
        $this->children = $this->resolveString($children);
        $this->comment = $comment;
        $this->arrow = $arrow;
        $this->isNullable = $isNullable;
    }

    public function __toString(): string
    {
        if ($this->isNullable) {
            $this->arrow = $this->arrow . ' "nullable"';
        }

        return \sprintf(self::BLOCK, $this->parent, $this->arrow, $this->children, $this->comment);
    }
}
