<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

interface ConstantsInterface
{
    /**
     * @return array<string, int>
     */
    public function all(): array;
}
