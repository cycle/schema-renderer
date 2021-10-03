<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Formatter;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;

/**
 * Colored text for console output
 */
final class StyledFormatter implements Formatter
{
    private const COLORS_MAP = [
        '</>' => "\033[39m",
        '<fg=red>' => "\033[31m",
        '<fg=green>' => "\033[32m",
        '<fg=yellow>' => "\033[33m",
        '<fg=blue>' => "\033[34m",
        '<fg=magenta>' => "\033[35m",
        '<fg=cyan>' => "\033[36m",
    ];

    public function title(string $title): string
    {
        return str_pad($title, self::TITLE_LENGTH, ' ', STR_PAD_LEFT);
    }

    public function property(string $string): string
    {
        return $this->colorize("<fg=cyan>{$string}</>");
    }

    public function column(string $string): string
    {
        return $this->colorize("<fg=green>{$string}</>");
    }

    public function info(string $string): string
    {
        return $this->colorize("<fg=yellow>{$string}</>");
    }

    public function typecast(string $string): string
    {
        return $this->colorize("<fg=blue>{$string}</>");
    }

    public function entity(string $string): string
    {
        return $this->colorize("<fg=magenta>{$string}</>");
    }

    public function error(string $string): string
    {
        return $this->colorize("<fg=red>{$string}</>");
    }

    private function colorize(string $string): string
    {
        return str_replace(
            array_keys(self::COLORS_MAP),
            array_values(self::COLORS_MAP),
            $string
        );
    }
}
