<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Exporter\Rendering;

use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ExporterItem;

final class ArrayRenderer
{
    public static function render(array $value, int $indentLevel = 0): string
    {
        $aiKeys = self::isAutoIncrementedKeys($value);
        $inline = $aiKeys && self::isScalarArrayValues($value);
        if ($inline) {
            $result = self::renderArrayInline($value, !$aiKeys);
            if (strlen($result) <= ExporterItem::MAX_LINE_LENGTH) {
                return $result;
            }
        }
        return self::renderArrayBlock($value, !$aiKeys, $indentLevel);
    }

    /**
     * @param mixed $value
     */
    private static function renderValue($value, bool $wrapValue = true, int $indentLevel = 0): string
    {
        return ValueRenderer::render($value, $wrapValue, $indentLevel);
    }

    private static function renderArrayInline(array $value, bool $withKeys = true): string
    {
        $elements = [];
        foreach ($value as $key => $item) {
            $str = '';
            if (!$item instanceof self && $withKeys) {
                $str .= is_int($key) ? "{$key} => " : "'{$key}' => ";
            }
            $elements[] = $str . self::renderValue($item);
        }
        return '[' . \implode(', ', $elements) . ']';
    }

    private static function renderArrayBlock(array $value, bool $withKeys = true, int $indentLevel = 0): string
    {
        $braceIdent = \str_repeat(Indentable::INDENT, $indentLevel);
        $itemIndent = \str_repeat(Indentable::INDENT, $indentLevel + 1);
        $result = '[';
        foreach ($value as $key => $item) {
            $result .= "\n" . $itemIndent;
            if ($item instanceof Indentable) {
                $item->setIndentLevel($indentLevel + 1);
            } elseif ($withKeys) {
                $result .= is_int($key) ? "{$key} => " : "'{$key}' => ";
            }
            $result .= self::renderValue($item, true, $indentLevel + 1) . ',';
        }
        return $result . "\n$braceIdent]";
    }

    private static function isAutoIncrementedKeys(array $array): bool
    {
        return count($array) === 0 || array_keys($array) === range(0, count($array) - 1);
    }

    private static function isScalarArrayValues(array $array): bool
    {
        foreach ($array as $value) {
            if (!is_scalar($value)) {
                return false;
            }
        }
        return true;
    }
}
