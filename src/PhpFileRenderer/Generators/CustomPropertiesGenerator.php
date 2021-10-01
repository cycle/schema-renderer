<?php

namespace Cycle\Schema\Renderer\PhpFileRenderer\Generators;

use Cycle\Schema\Renderer\PhpFileRenderer\Generator;
use Cycle\Schema\Renderer\PhpFileRenderer\VarExporter;

class CustomPropertiesGenerator implements Generator
{
    private array $propertiesWithGenerator;

    public function __construct(array $propertiesWithGenerator)
    {
        $this->propertiesWithGenerator = $propertiesWithGenerator;
    }

    public function generate(array $schema, string $role): array
    {
        $properties = [];

        foreach ($schema as $key => $data) {
            if (in_array($key, $this->propertiesWithGenerator, true)) {
                continue;
            }
            $properties[] = new VarExporter($key, $data, is_string($key));
        }

        return $properties;
    }
}
