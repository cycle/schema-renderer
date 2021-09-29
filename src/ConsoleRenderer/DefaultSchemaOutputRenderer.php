<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\ConsoleRenderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderers\ColumnsRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderers\CustomPropertiesRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderers\PrimaryKeysRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderers\PropertyRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderers\RelationsRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderers\TitleRenderer;

class DefaultSchemaOutputRenderer extends OutputRenderer
{
    protected const DEFAULT_PROPERTY_LIST = [
        SchemaInterface::ROLE => 'Role',
        SchemaInterface::ENTITY => 'Entity',
        SchemaInterface::MAPPER => 'Mapper',
        SchemaInterface::SCOPE => 'Constrain',
        SchemaInterface::REPOSITORY => 'Repository',
    ];

    public function __construct(array $schema, Formatter $formatter)
    {
        parent::__construct($schema, $formatter);

        $this->addRenderer(...[
            new TitleRenderer(),

            // Default properties renderer (Without extra logic)
            ...array_map(static function ($property, string $title) {
                return new PropertyRenderer($property, $title);
            }, array_keys(static::DEFAULT_PROPERTY_LIST), static::DEFAULT_PROPERTY_LIST),

            new PrimaryKeysRenderer(),
            new ColumnsRenderer(),
            new RelationsRenderer(),
            new CustomPropertiesRenderer(),
        ]);
    }
}
