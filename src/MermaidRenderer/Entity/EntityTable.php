<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Entity;

use Cycle\Schema\Renderer\MermaidRenderer\Annotation;
use Cycle\Schema\Renderer\MermaidRenderer\Method;
use Cycle\Schema\Renderer\MermaidRenderer\Column;
use Cycle\Schema\Renderer\MermaidRenderer\Stringable;

final class EntityTable implements EntityInterface
{
    private const BLOCK = <<<BLOCK
        class %s {
            %s
        }
        BLOCK;

    public const INDENT = '    ';

    private string $title;

    /**
     * @var Annotation[]|Column[]|Method[]
     */
    private array $columns = [];

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function addColumn(Column $column): void
    {
        $this->columns[] = $column;
    }

    public function addMethod(Method $method): void
    {
        $this->columns[] = $method;
    }

    public function addAnnotation(Annotation $annotation): void
    {
        \array_unshift($this->columns, $annotation);
    }

    public function __toString(): string
    {
        $columns = \array_map(function (Stringable $column) {
            return (string) $column;
        }, $this->columns);

        $body = \implode("\n" . self::INDENT, $columns);

        return \sprintf(self::BLOCK, $this->title, $body);
    }
}
