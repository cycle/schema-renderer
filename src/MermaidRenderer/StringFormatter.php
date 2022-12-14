<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

trait StringFormatter
{
    private function resolveString(string $value): string
    {
        if (\preg_match('/[^_a-zA-Z0-9]/u', $value)) {
            $value = \preg_replace('/[^a-z0-9_]/u', '_', $value);
        }

        return $value;
    }

    /**
     * @param class-string|object $class
     */
    private function getClassShortName($class): string
    {
        $className = \is_object($class) ? \get_class($class) : $class;

        return \substr($className, ((int)\strrpos($className, '\\')) + 1);
    }
}
