<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\PhpFileRendere\Generator;

final class SchemaToPhpFileRenderer
{
    private const USE_LIST = [
        'Cycle\ORM\Relation',
        'Cycle\ORM\SchemaInterface',
    ];

    public function render(SchemaInterface $schema): string
    {
        $result = "<?php\n\ndeclare(strict_types=1);\n\n";

        // the use block
        foreach (self::USE_LIST as $use) {
            $result .= "use {$use};\n";
        }

        $schemas = array_filter(
            array_map(static function (string $role) use ($schema) {
                return (new Generator($schema))->generate($role);
            }, $schema->getRoles())
        );

        $renderedArray = implode(",\n", $schemas);

        return $result . "\nreturn [\n{$renderedArray}\n];";
    }
}
