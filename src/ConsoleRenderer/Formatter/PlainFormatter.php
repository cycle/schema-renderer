<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Formatter;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;

final class PlainFormatter implements Formatter
{
    #[\Override]
    public function title(string $title): string
    {
        return \str_pad($title, self::TITLE_LENGTH, ' ', STR_PAD_LEFT);
    }

    #[\Override]
    public function property(string $string): string
    {
        return $string;
    }

    #[\Override]
    public function column(string $string): string
    {
        return $string;
    }

    #[\Override]
    public function info(string $string): string
    {
        return $string;
    }

    #[\Override]
    public function typecast(string $string): string
    {
        return $string;
    }

    #[\Override]
    public function entity(string $string): string
    {
        return $string;
    }

    #[\Override]
    public function error(string $string): string
    {
        return $string;
    }
}
