<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Entity;

final class EntityArrow implements EntityInterface
{
    private string $title;
    private array $arrows = [];

    const BLOCK = '%s %s %s : %s';
    const NOT_NULLABLE = '||--|{';
    const NULLABLE = '||--o{';

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function addArrow(string $children, string $relation, bool $isNullable = false): void
    {
        $this->arrows[] = [$children, $relation, $isNullable];
    }

    public function toString(): string
    {
        $arrows = [];

        foreach ($this->arrows as $arrow) {
            $arrows[] = sprintf(
                self::BLOCK,
                $this->title,
                $arrow[2] ? self::NULLABLE : self::NOT_NULLABLE,
                $arrow[0],
                $arrow[1]
            );
        }

        return implode("\n", $arrows);
    }
}
