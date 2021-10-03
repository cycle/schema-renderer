<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Exporter;

use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\Rendering\Indentable;

class ArrayBlock implements ExporterItem, Indentable
{
    protected array $value;
    protected array $replaceKeys;
    protected array $replaceValues;
    private int $indentLevel = 0;

    public function __construct(array $value, array $replaceKeys = [], array $replaceValues = [])
    {
        $this->value = $value;
        $this->replaceKeys = $replaceKeys;
        $this->replaceValues = $replaceValues;
    }

    final public function setIndentLevel(int $indentLevel = 0): self
    {
        $this->indentLevel = $indentLevel;
        return $this;
    }

    final public function toString(): string
    {
        $result = [];
        foreach ($this->value as $key => $value) {
            if ($value instanceof ArrayItem) {
                $value->setIndentLevel($this->indentLevel + 1);
                $result[] = $value->toString();
                continue;
            }
            $item = $this->wrapItem($key, $value);
            $item->setIndentLevel($this->indentLevel + 1);

            $result[] = $item->toString();
        }
        $indent = \str_repeat(self::INDENT, $this->indentLevel + 1);
        $closedIndent = \str_repeat(self::INDENT, $this->indentLevel);
        return $result === []
            ? '[]'
            : "[\n$indent" . \implode(",\n$indent", $result) . ",\n$closedIndent]";
    }

    /**
     * @param int|string $key
     * @param mixed $value
     */
    protected function wrapItem($key, $value): ArrayItem
    {
        $item = isset($this->replaceKeys[$key])
            ? new ArrayItem($value, (string)$this->replaceKeys[$key], false)
            : new ArrayItem($value, (string)$key, true);

        if (is_scalar($value) && isset($this->replaceValues[$key][$value])) {
            $item->setValue($this->replaceValues[$key][$value], false);
        }
        return $item;
    }
}
