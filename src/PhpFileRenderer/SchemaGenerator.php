<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer;

class SchemaGenerator implements Generator
{
    /** @var Generator[] */
    private array $generators;

    public function __construct(array $generators = [])
    {
        $this->addGenerator(...$generators);
    }

    public function addGenerator(Generator ...$generators): void
    {
        foreach ($generators as $generator) {
            $this->generators[] = $generator;
        }
    }

    public function generate(array $schema, string $role): VarExporter
    {
        /** @var array<int, string> $array */
        $array = [];

        foreach ($this->generators as $generator) {
            $array[] = $generator->generate($schema, $role);
        }

        return new VarExporter($role, $array, true);
    }
}
