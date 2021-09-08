<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleOutput;

use Traversable;

class Formatter
{
    private const TITLE_LENGTH = 12;

    public function title(string $title): string
    {
        return str_pad($title, self::TITLE_LENGTH, ' ', STR_PAD_LEFT);
    }

    public function formatColors(iterable $rows): Traversable
    {
        foreach ($rows as $row) {
            yield str_replace([
                '</>', '<fg=red>', '<fg=green>', '<fg=yellow>', '<fg=blue>', '<fg=magenta>', '<fg=cyan>',
            ], [
                "\033[39m", "\033[31m", "\033[32m", "\033[33m", "\033[34m", "\033[35m", "\033[36m",
            ], $row);
        }
    }
}
