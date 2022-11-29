<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Entity;

final class EntityTable implements EntityInterface
{
    private string $title;
    private array $rows = [];

    private const BLOCK = <<<BLOCK
        class %s {
            %s
        }
        BLOCK;

    public const INDENT = '    ';
    public const SEPARATOR = ' ';

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function addRow(string ...$values): void
    {
        $this->rows[] = $values;
    }

    public function addMethod(string $name, string ...$params): void
    {
        $row = \sprintf('%s(%s)', $name, implode('', $params));

        $this->rows[] = [$row];
    }

    public function addAnnotation(string $name): void
    {
        $row = "<<$name>>";

        \array_unshift($this->rows, [$row]);
    }

    public function __toString(): string
    {
        $rows = [];

        foreach ($this->rows as $row) {
            $rows[] = \implode(self::SEPARATOR, $row);
        }

        $body = \implode("\n" . self::INDENT, $rows);

        return \sprintf(self::BLOCK, $this->title, $body);
    }
}
