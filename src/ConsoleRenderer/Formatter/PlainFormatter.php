<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer\Formatter;

use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;

final class PlainFormatter implements Formatter
{
    public function title(string $title): string
    {
        return str_pad($title, self::TITLE_LENGTH, ' ', STR_PAD_LEFT);
    }

    public function property(string $string): string
    {
        return $string;
    }

    public function column(string $string): string
    {
        return $string;
    }

    public function info(string $string): string
    {
        return $string;
    }

    public function typecast(string $string): string
    {
        return $string;
    }

    public function entity(string $string): string
    {
        return $string;
    }

    public function error(string $string): string
    {
        return $string;
    }
}
