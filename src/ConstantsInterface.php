<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

interface ConstantsInterface
{
    /**
     * @return array<string, mixed>
     */
    public function all(): array;
}
