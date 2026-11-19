<div align="center">
    <a href="https://cycle-orm.dev" target="_blank">
        <picture>
            <source media="(prefers-color-scheme: dark)" srcset="https://github.com/cycle/.github/blob/main/logo/words-vector-dark.svg?raw=true">
            <img width="50%" align="center" src="https://github.com/cycle/.github/blob/main/logo/words-vector-light.svg?raw=true" alt="CycleORM Logo">
        </picture>
    </a>
</div>

<p align="center">Render a Cycle ORM schema for a terminal, a PHP file or a Mermaid diagram</p>

<div align="center">

[![Documentation](https://img.shields.io/badge/Documentation-blue?style=for-the-badge&logo=gitbook&logoColor=white)](https://cycle-orm.dev/docs)
[![Sponsor](https://img.shields.io/static/v1?style=for-the-badge&label=&message=Sponsor&logo=githubsponsors&logoColor=white&color=%23EA4AAA)](https://github.com/sponsors/cycle)
[![Discord](https://img.shields.io/discord/538114875570913290?style=for-the-badge&logo=discord&labelColor=7289d9&logoColor=white&color=39456d)](https://discord.gg/spiralphp)

[![Psalm Level](https://shepherd.dev/github/cycle/schema-renderer/level.svg)](https://shepherd.dev/github/cycle/schema-renderer)
[![Type Coverage](https://shepherd.dev/github/cycle/schema-renderer/coverage.svg)](https://shepherd.dev/github/cycle/schema-renderer)
[![Codecov](https://img.shields.io/codecov/c/github/cycle/schema-renderer/1.x?style=flat-square&logo=codecov)](https://codecov.io/gh/cycle/schema-renderer)

</div>

<br />

This package renders a [Cycle ORM](https://github.com/cycle/orm) schema in a human-readable form: as text for a terminal, as a PHP file that returns the schema array, or as a Mermaid class diagram.

## Get Started

### Installation

The package requires PHP 8.2+.

```bash
composer require cycle/schema-renderer
```

[![PHP](https://img.shields.io/packagist/php-v/cycle/schema-renderer.svg?style=flat-square&logo=php)](https://packagist.org/packages/cycle/schema-renderer)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/cycle/schema-renderer.svg?style=flat-square&logo=packagist)](https://packagist.org/packages/cycle/schema-renderer)
[![License](https://img.shields.io/packagist/l/cycle/schema-renderer.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/cycle/schema-renderer.svg?style=flat-square)](https://packagist.org/packages/cycle/schema-renderer/stats)

### Convert schema to array

```php
$schema = new Schema([...]);
$converter = new \Cycle\Schema\Renderer\SchemaToArrayConverter();

$schemaArray = $converter->convert($schema);
```

If the passed `SchemaInterface` doesn't have a `toArray()` method, the `SchemaToArrayConverter` converts
only the common properties from `Cycle\ORM\SchemaInterface` and skips null values.

To keep custom properties, pass their keys as the second argument:

```php
$converter = new \Cycle\Schema\Renderer\SchemaToArrayConverter();

$schemaArray = $converter->convert($schema, [
    42,
    CustomClass::CUSTOM_PROPERTY,
    ...
]);
```

### Render schema to a terminal

```php
use Cycle\Schema\Renderer\OutputSchemaRenderer;

$output = new \Symfony\Component\Console\Output\ConsoleOutput();

$renderer = new OutputSchemaRenderer(format: OutputSchemaRenderer::FORMAT_CONSOLE_COLOR);

$output->write($renderer->render($schemaArray));
```

By default, `OutputSchemaRenderer` renders only the common properties and relations with its template.
Custom properties are rendered as is in a separate block.
To extend the default template, create custom renderers and add them to the output renderer.

```php
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer\PropertyRenderer;
use Cycle\Schema\Renderer\OutputSchemaRenderer;

class CustomPropertyRenderer implements Renderer
{
    public function render(Formatter $formatter, array $schema, string $role): string
    {
        $data = $schema[CustomClass::CUSTOM_PROPERTY] ?? null;

        return \sprintf(
            '%s: %s',
            $formatter->title('My custom property'),
            $data === null ? $formatter->error('not defined') : $formatter->typecast($data),
        );
    }
}

$renderer = new OutputSchemaRenderer();

$renderer->addRenderer(
    new CustomPropertyRenderer(),
    new PropertyRenderer(CustomClass::OTHER_PROPERTY, 'My super property'),
);

$output->write($renderer->render($schemaArray));
```

### Store schema in a PHP file

```php
use Cycle\Schema\Renderer\PhpSchemaRenderer;

$path = __DIR__ . '/schema.php';

$renderer = new PhpSchemaRenderer();

\file_put_contents($path, $renderer->render($schemaArray));
```

The renderer generates valid PHP code in which constants from Cycle ORM classes replace raw keys
for better readability.

### Render schema as a Mermaid diagram

```php
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;

$diagram = (new MermaidRenderer())->render($schemaArray);
```

The result is a Mermaid class diagram of the entities and their relations.
