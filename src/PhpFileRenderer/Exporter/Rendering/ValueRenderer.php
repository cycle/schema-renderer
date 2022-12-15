<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Exporter\Rendering;

use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ExporterItem;

final class ValueRenderer
{
    /**
     * @param mixed $value
     */
    public static function render($value, bool $wrapValue = true, int $indentLevel = 0): string
    {
        switch (true) {
            case $value === null:
                return 'null';
            case is_bool($value):
                return $value ? 'true' : 'false';
            case is_array($value):
                return ArrayRenderer::render($value, $indentLevel);
            case $value instanceof ExporterItem:
                return $value->toString();
            case !$wrapValue || is_int($value):
                return (string)$value;
            case \is_string($value) && \strpos($value, '\\') !== false && \class_exists($value):
                return "$value::class";
            case is_string($value):
                return "'" . addslashes($value) . "'";
            default:
                return "unserialize('" . addslashes(serialize($value)) . "')";
        }
    }
}
