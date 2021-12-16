<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\PlainFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter\StyledFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\OutputRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\ColumnsRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\CustomPropertiesRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\KeysRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\ListenersRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\PropertyRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\RelationsRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\TitleRenderer;

/**
 * The class is designed to prepare a human-readable representation of the Cycle ORM Schema for output to the console
 */
final class OutputSchemaRenderer extends OutputRenderer
{
    public const FORMAT_PLAIN_TEXT = 0;
    public const FORMAT_CONSOLE_COLOR = 1;

    protected const DEFAULT_PROPERTY_LIST = [
        'ROLE' => 'Role',
        'ENTITY' => 'Entity',
        'MAPPER' => 'Mapper',
        'SCOPE' => 'Scope',
        'REPOSITORY' => 'Repository',
    ];

    public function __construct(int $format = self::FORMAT_CONSOLE_COLOR, ?ConstantsInterface $constants = null)
    {
        $formatter = $format === self::FORMAT_CONSOLE_COLOR
            ? new StyledFormatter()
            : new PlainFormatter();
        parent::__construct($formatter);

        $constants = ($constants ?? new SchemaConstants())->all();
        $properties = $this->getOrmProperties($constants);

        $this->addRenderer(...[
            new TitleRenderer(),

            // Default properties renderer (Without extra logic)
            ...array_map(static function ($property, string $title) {
                return new PropertyRenderer($property, $title);
            }, array_keys($properties), $properties),

            new KeysRenderer(SchemaInterface::PRIMARY_KEY, 'Primary key', true),
        ]);

        // JTI support
        if (isset($constants['PARENT'], $constants['PARENT_KEY'])) {
            $this->addRenderer(...[
                new PropertyRenderer($constants['PARENT'], 'Parent'),
                new KeysRenderer($constants['PARENT_KEY'], 'Parent key', false),
            ]);
        }

        // STI support
        if (isset($constants['CHILDREN'])) {
            $this->addRenderer(new PropertyRenderer($constants['CHILDREN'], 'Children'));
        }

        if (isset($constants['DISCRIMINATOR'])) {
            $this->addRenderer(new KeysRenderer($constants['DISCRIMINATOR'], 'Discriminator', false));
        }

        $this->addRenderer(new ColumnsRenderer());
        if (isset($constants['TYPECAST_HANDLER'])) {
            $this->addRenderer(new PropertyRenderer($constants['TYPECAST_HANDLER'], 'Typecast'));
        }

        if (isset($constants['LISTENERS'])) {
            $this->addRenderer(new ListenersRenderer($constants['LISTENERS'], 'Listeners'));
        }

        $this->addRenderer(
            new RelationsRenderer(),
            new CustomPropertiesRenderer(\array_values($constants)),
        );
    }

    /**
     * @param array<string, int> $constants
     *
     * @return array<int, string>
     */
    private function getOrmProperties(array $constants): array
    {
        $result = [];
        foreach ($constants as $name => $value) {
            if (!array_key_exists($name, self::DEFAULT_PROPERTY_LIST)) {
                continue;
            }
            $result[$value] = self::DEFAULT_PROPERTY_LIST[$name];
        }
        return $result;
    }
}
