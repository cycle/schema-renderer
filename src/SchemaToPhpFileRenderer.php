<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer;
use Cycle\Schema\Renderer\PhpFileRenderer\Generator;

final class SchemaToPhpFileRenderer
{
    private const USE_LIST = [
        'Cycle\ORM\Relation',
        'Cycle\ORM\SchemaInterface',
    ];

    private Generator $generator;
    private array $schema;

    public function __construct(array $schema, Generator $generator)
    {
        $this->generator = $generator;
        $this->schema = $schema;
    }

    public function render(): string
    {
        $schema = [];

        $result = "<?php\n\ndeclare(strict_types=1);\n\n";

        // the use block
        foreach (self::USE_LIST as $use) {
            $result .= "use {$use};\n";
        }

        foreach ($this->schema as $role => $roleSchema) {
            $schema[] = $this->generator->generate($roleSchema, $role);
        }

        $renderedArray = implode(",\n", $schema);

        return $result . "\nreturn [\n{$renderedArray}\n];";
    }
}
