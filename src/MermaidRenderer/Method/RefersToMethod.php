<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Method;

use Cycle\Schema\Renderer\MermaidRenderer\Method;

final class RefersToMethod extends Method
{
    public function __construct(string $name, string $target)
    {
        parent::__construct($name, $target, 'RT');
    }
}
