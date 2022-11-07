<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Entity;

final class EntityTable implements EntityInterface
{
    private string $title;
    private array $rows = [];

    public const BLOCK = <<<BLOCK
        %s {
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

    public function toString(): string
    {
        $rows = [];

        foreach ($this->rows as $row) {
            $rows[] = implode(self::SEPARATOR, $row);
        }

        $body = implode("\n" . self::INDENT, $rows);

        return sprintf(self::BLOCK, $this->title, $body);
    }
}
