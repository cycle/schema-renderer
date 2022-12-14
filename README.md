# Cycle ORM Schema renderer

[![Latest Stable Version](https://poser.pugx.org/cycle/schema-renderer/v/stable)](https://packagist.org/packages/cycle/schema-renderer)
[![build](https://github.com/cycle/schema-renderer/actions/workflows/main.yml/badge.svg)](https://github.com/cycle/schema-renderer/actions/workflows/main.yml)
[![static analysis](https://github.com/cycle/schema-renderer/actions/workflows/static.yml/badge.svg)](https://github.com/cycle/schema-renderer/actions/workflows/static.yml)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cycle/schema-renderer/badges/quality-score.png?b=1.x)](https://scrutinizer-ci.com/g/cycle/schema-renderer/?branch=1.x)
[![Codecov](https://codecov.io/gh/cycle/schema-renderer/branch/1.x/graph/badge.svg)](https://codecov.io/gh/cycle/schema-renderer/)
[![StyleCI](https://github.styleci.io/repos/401633317/shield?branch=1.x)](https://github.styleci.io/repos/401633317?branch=1.x)

This package may be used to render Cycle ORM Schema in a terminal or generate php representation.

## Installation

The preferred way to install this package is through [Composer](https://getcomposer.org/download/):

```bash
composer require cycle/schema-renderer
```

## Example

### Convert schema to array

```php
$schema = new Schema([...]);
$converter = new \Cycle\Schema\Renderer\SchemaToArrayConverter();

$schemaArray = $converter->convert($schema);
```

If passed `SchemaInterface` doesn't contain `toArray()` method then the `SchemaToArrayConverter`  will convert
only common properties from `Cycle\ORM\SchemaInterface`. Null values will be skipped also.

In this case Iif you want to use custom properties you can pass them to the constructor

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

$renderer = new OutputSchemaRenderer(colorize: true);

$output->write($renderer->render($schemaArray));
```

By default, `DefaultSchemaOutputRenderer` renders in template only common properties and relations.
Custom properties will be rendered as is in separated block.
If you want to extend default rendering template you can create custom renderers and add them to the Output renderer.

```php
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;
use Cycle\Schema\Renderer\OutputSchemaRenderer;

class CustomPropertyRenderer implements Renderer {

    public function render(Formatter $formatter, array $schema, string $role): string
    {
        $data = $schema['my_custom_property'] ?? null;

        return \sprintf(
            '%s: %s',
            $formatter->title($this->title),
            $data === null ? $formatter->error('not defined') : $formatter->typecast($data)
        );
    }
}

$renderer = new OutputSchemaRenderer();

$renderer->addRenderer(
    new CustomPropertyRenderer(),
    new PropertyRenderer('my_custom_property', 'My super property')
);

$output->write($renderer->render($schemaArray))
```

### Store schema in a PHP file

```php
use Cycle\Schema\Renderer\PhpSchemaRenderer;

$path = __DIR__. '/schema.php'

$renderer = new PhpSchemaRenderer();

\file_put_contents($path, $renderer->render($schemaArray));
```

The Renderer generates valid PHP code, in which constants from Cycle ORM classes are substituted
for better readability.

## License:

The MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information.
Maintained by [Spiral Scout](https://spiralscout.com).

