<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\PhpFileRenderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\PhpFileRenderer\Generators\PrimitiveGenerator;
use Cycle\Schema\Renderer\PhpFileRenderer\Generators\RelationsGenerator;

class DefaultSchemaGenerator extends SchemaGenerator
{
    protected const PRIMITIVE_KEYS = [
        'SchemaInterface::DATABASE' => SchemaInterface::DATABASE,
        'SchemaInterface::TABLE' => SchemaInterface::TABLE,
        'SchemaInterface::ENTITY' => SchemaInterface::ENTITY,
        'SchemaInterface::MAPPER' => SchemaInterface::MAPPER,
        'SchemaInterface::REPOSITORY' => SchemaInterface::REPOSITORY,
        'SchemaInterface::SCOPE' => SchemaInterface::SCOPE,
        'SchemaInterface::PRIMARY_KEY' => SchemaInterface::PRIMARY_KEY,
        'SchemaInterface::COLUMNS' => SchemaInterface::COLUMNS,
        'SchemaInterface::TYPECAST' => SchemaInterface::TYPECAST,
    ];

    public function __construct(array $generators = [])
    {
        parent::__construct([
            ...$generators,

            ... array_map(static function (string $key, $property) {
                return new PrimitiveGenerator($key, $property);
            }, array_keys(self::PRIMITIVE_KEYS), self::PRIMITIVE_KEYS),

            new RelationsGenerator()
        ]);
    }
}
