<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer;

use Cycle\Schema\Renderer\SchemaRenderer;

class OutputRenderer implements SchemaRenderer
{
    /** @var Renderer[] */
    private array $renderers = [];
    private Formatter $formatter;

    public function __construct(Formatter $formatter, array $renderers = [])
    {
        $this->formatter = $formatter;
        $this->addRenderer(...$renderers);
    }

    final public function addRenderer(Renderer ...$renderers): void
    {
        foreach ($renderers as $renderer) {
            $this->renderers[] = $renderer;
        }
    }

    final public function render(array $schema): string
    {
        $result = '';
        foreach ($schema as $role => $roleSchema) {
            $result .= $this->renderRole($roleSchema, $role) . $this->formatter::ROLE_BLOCK_SEPARATOR;
        }
        return $result;
    }

    private function renderRole(array $schema, string $role): string
    {
        $rows = [];

        foreach ($this->renderers as $renderer) {
            if ($row = $renderer->render($this->formatter, $schema, $role)) {
                $rows[] = $row;
            }
        }

        return \implode($this->formatter::LINE_SEPARATOR, $rows);
    }
}
