<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Exporter;

use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\Rendering\Indentable;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\Rendering\ValueRenderer;

final class ArrayItem implements ExporterItem, Indentable
{
    public ?string $key;

    /** @var mixed */
    private $value;
    private bool $wrapValue = true;
    private bool $wrapKey;
    private int $indentLevel = 0;

    /**
     * @param mixed $value
     */
    public function __construct($value, string $key = null, bool $wrapKey = true)
    {
        $this->key = $key;
        $this->value = $value;
        $this->wrapKey = $wrapKey;
    }

    public function setIndentLevel(int $indentLevel = 0): self
    {
        $this->indentLevel = $indentLevel;
        return $this;
    }

    public function toString(): string
    {
        $result = \str_repeat(self::INDENT, $this->indentLevel);
        if ($this->key !== null) {
            $result = $this->wrapKey ? "'{$this->key}' => " : "{$this->key} => ";
        }
        if ($this->value instanceof Indentable) {
            $this->value->setIndentLevel($this->indentLevel);
        }
        return $result . ValueRenderer::render($this->value, $this->wrapValue, $this->indentLevel);
    }

    /**
     * @param mixed $value
     */
    public function setValue($value, bool $wrapValue = true): self
    {
        $this->value = $value;
        $this->wrapValue = $wrapValue;

        return $this;
    }
}
