<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Entity;

use Cycle\Schema\Renderer\MermaidRenderer\Annotation;
use Cycle\Schema\Renderer\MermaidRenderer\Method;
use Cycle\Schema\Renderer\MermaidRenderer\Row;

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

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function addRow(Row $row): void
    {
        $this->rows[] = $row;
    }

    public function addMethod(Method $method): void
    {
        $this->rows[] = $method;
    }

    public function addAnnotation(Annotation $annotation): void
    {
        \array_unshift($this->rows, $annotation);
    }

    public function __toString(): string
    {
        $rows = [];

        foreach ($this->rows as $row) {
            $rows[] = (string)$row;
        }

        $body = \implode("\n" . self::INDENT, $rows);

        return \sprintf(self::BLOCK, $this->title, $body);
    }
}
