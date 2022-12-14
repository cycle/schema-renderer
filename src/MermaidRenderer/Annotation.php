<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

final class Annotation implements Stringable
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = \htmlentities($name);
    }

    public function __toString(): string
    {
        return "<<$this->name>>";
    }
}
