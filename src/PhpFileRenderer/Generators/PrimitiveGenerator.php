<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer\Generators;

use Cycle\Schema\Renderer\PhpFileRenderer\Generator;
use Cycle\Schema\Renderer\PhpFileRenderer\VarExporter;

class PrimitiveGenerator implements Generator
{
    private string $key;

    /** @var string|int */
    private $property;

    public function __construct(string $key, $property)
    {
        $this->key = $key;
        $this->property = $property;
    }

    public function generate(array $schema, string $role): VarExporter
    {
        return new VarExporter($this->key, $schema[$this->property] ?? null);
    }
}
