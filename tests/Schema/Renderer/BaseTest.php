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
            if (! \defined(SchemaInterface::class . '::' . $constant)) {
                return false;
            }
        }

        return true;
    }

    public function isOrmV2(): bool
    {
        return (int) \Composer\InstalledVersions::getVersion('cycle/orm') > 1;
    }

    public function isOrmVersion(string $version): bool
    {
        return \Composer\InstalledVersions::getVersion('cycle/orm') === $version;
    }
}
