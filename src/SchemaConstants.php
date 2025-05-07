<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

use Cycle\ORM\SchemaInterface;

class SchemaConstants implements ConstantsInterface
{
    #[\Override]
    public function all(): array
    {
        return array_filter(
            (new \ReflectionClass(SchemaInterface::class))->getConstants(),
            'is_int'
        );
    }
}
