<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Entity;

interface EntityInterface
{
    public function toString(): string;
}
