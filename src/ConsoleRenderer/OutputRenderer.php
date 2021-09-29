<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer;

use IteratorAggregate;
use Traversable;

class OutputRenderer implements IteratorAggregate
{
    /** @var Renderer[] */
    private array $renderers = [];
    private array $schema;
    private Formatter $formatter;

    public function __construct(array $schema, Formatter $formatter, array $renderers = [])
    {
        $this->addRenderer(...$renderers);

        $this->formatter = $formatter;
        $this->schema = $schema;
    }

    public function addRenderer(Renderer ...$renderers): void
    {
        foreach ($renderers as $renderer) {
            $this->renderers[] = $renderer;
        }
    }

    /**
     * @return Traversable<string, string>
     */
    public function getIterator(): Traversable
    {
        foreach ($this->schema as $role => $schema) {
            yield $role => $this->renderSchema($schema, $role);
        }
    }

    private function renderSchema(array $schema, string $role): string
    {
        $rows = [];

        foreach ($this->renderers as $renderer) {
            $rows[] = $renderer->render($this->formatter, $schema, $role);
        }

        return implode("\n", $rows);
    }
}
