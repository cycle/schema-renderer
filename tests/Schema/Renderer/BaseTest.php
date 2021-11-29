<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests;

use Cycle\ORM\SchemaInterface;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    public function constantSupported(string ...$constants): bool
    {
        foreach ($constants as $constant) {
            if (! \defined(SchemaInterface::class.'::'.$constant)) {
                return false;
            }
        }

        return true;
    }
}
