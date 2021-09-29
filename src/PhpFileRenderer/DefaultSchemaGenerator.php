<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\PhpFileRenderer\Generators\CustomPropertiesGenerator;
use Cycle\Schema\Renderer\PhpFileRenderer\Generators\PrimitiveGenerator;
use Cycle\Schema\Renderer\PhpFileRenderer\Generators\RelationsGenerator;

class DefaultSchemaGenerator extends SchemaGenerator
{
    public function __construct(array $generators = [])
    {
        $refl = new \ReflectionClass(SchemaInterface::class);

        foreach ($refl->getConstants() as $key => $value) {
            if (!array_key_exists($value, $generators)) {
                if ($key === 'RELATIONS') {
                    $generators[$value] = new RelationsGenerator();
                    continue;
                }

                $generators[$value] = new PrimitiveGenerator($key, 'SchemaInterface::' . $key);
            }
        }

        parent::__construct([
            ...$generators,
            //new CustomPropertiesGenerator(array_keys($generators))
        ]);
    }
}
