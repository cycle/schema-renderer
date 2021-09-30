<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer;

class SchemaGenerator implements Generator
{
    /** @var Generator[] */
    private array $generators;

    public function __construct(array $generators = [])
    {
        $this->generators = $generators;
    }

    public function generate(array $schema, string $role): array
    {
        /** @var array<int, VarExporter> $array */
        $array = [];

        foreach ($this->generators as $generator) {
            foreach ($generator->generate($schema, $role) as $property) {
                $array[] = $property;
            }
        }

        return $array;
    }
}
