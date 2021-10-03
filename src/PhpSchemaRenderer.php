<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayBlock;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ArrayItem;
use Cycle\Schema\Renderer\PhpFileRenderer\Exporter\ExporterItem;
use Cycle\Schema\Renderer\PhpFileRenderer\Item\RoleBlock;

/**
 * The Renderer generates valid PHP code, in which constants from {@see \Cycle\ORM\Relation} and
 * {@see \Cycle\ORM\SchemaInterface} are substituted for better readability.
 */
class PhpSchemaRenderer implements SchemaRenderer
{
    private const USE_LIST = [
        'Cycle\ORM\Relation',
        'Cycle\ORM\SchemaInterface as Schema',
    ];

    final public function render(array $schema): string
    {
        $result = "<?php\n\ndeclare(strict_types=1);\n\n";

        // The `use` block
        foreach (self::USE_LIST as $use) {
            $result .= "use {$use};\n";
        }

        $items = [];
        foreach ($schema as $role => $roleSchema) {
            $item = new ArrayItem(
                $this->wrapRoleSchema($roleSchema),
                $role,
                true
            );
            $items[] = $item->setIndentLevel();
        }

        $rendered = (new ArrayBlock($items))->toString();

        return $result . "\nreturn {$rendered};\n";
    }

    protected function wrapRoleSchema(array $roleSchema): ExporterItem
    {
        return new RoleBlock(
            $roleSchema,
            $this->getSchemaConstants()
        );
    }

    /**
     * @return array<int, string> (Option value => constant name) array
     */
    final protected function getSchemaConstants(): array
    {
        $result = array_filter(
            (new \ReflectionClass(SchemaInterface::class))->getConstants(),
            static fn ($value): bool => is_int($value)
        );
        $result = array_flip($result);
        array_walk($result, static function (string &$name): void {
            $name = "Schema::$name";
        });
        return $result;
    }
}
