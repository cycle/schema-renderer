<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

class Method implements Stringable
{
    private string $name;
    private string $target;
    private string $abbreviation;

    public function __construct(string $name, string $target, string $abbreviation)
    {
        $this->name = $name;
        $this->target = $target;
        $this->abbreviation = $abbreviation;
    }

    public function __toString(): string
    {
        return \sprintf(
            '%s(%s: %s)',
            $this->name,
            $this->abbreviation,
            $this->target
        );
    }
}
