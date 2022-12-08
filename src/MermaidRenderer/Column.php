<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer;

/**
 * @psalm-type Ttype=class-string|string|\Closure|object|array{0: class-string, 1: non-empty-string}
 */
final class Column implements Stringable
{
    use StringFormatter;

    private string $type;
    private string $column;

    /**
     * @param Ttype $type
     */
    public function __construct($type, string $column)
    {
        $this->type = $this->formatTypecast($type);
        $this->column = $column;
    }

    public function __toString(): string
    {
        return "$this->type $this->column";
    }

    /**
     * @param Ttype $typecast
     *
     * @return string
     */
    private function formatTypecast($typecast): string
    {
        if (\is_array($typecast)) {
            $typecast[0] = $this->getClassShortName($typecast[0]);

            return \sprintf("[%s, '%s']", "$typecast[0]::class", $typecast[1]);
        }

        if ($typecast instanceof \Closure) {
            return 'Closure';
        }

        if ($typecast === 'datetime') {
            return $typecast;
        }

        if (\is_object($typecast) || \class_exists($typecast)) {
            return $this->getClassShortName($typecast) . '::class';
        }

        return \htmlentities($typecast, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401);
    }
}
