# Cycle ORM Schema renderer

This package may be used to render schema roles in a terminal or generate php representation for CycleORM schema.

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
use Cycle\Schema\Renderer\ConsoleRenderer\DefaultSchemaOutputRenderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatters\StyledFormatter;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatters\PlainFormatter;

$output = new \Symfony\Component\Console\Output\ConsoleOutput();

$formatter = new StyledFormatter(); // Colorized output
// or
$formatter = new PlainFormatter(); // Plain output without colors

$renderer = new DefaultSchemaOutputRenderer($schemaArray, $formatter);

foreach ($renderer as $role => $rows) {
    $output->writeln($rows);
}
```

By default, DefaultSchemaOutputRenderer renders only common properties.
If you want to extend default CycleORM schema you can create custom renderers and add them to the Output renderer.

```php
use Cycle\Schema\Renderer\ConsoleRenderer\Renderer;
use Cycle\Schema\Renderer\ConsoleRenderer\Formatter;

class CustomPropertyRenderer implements Renderer {

    public function render(Formatter $formatter, array $schema, string $role): string
    {
        $data = $schema['my_custom_property'] ?? null;

        return sprintf(
            '%s: %s',
            $formatter->title($this->title),
            $data === null ? $formatter->error('not defined') : $formatter->typecast($data)
        );
    }
}

$renderer = new DefaultSchemaOutputRenderer($schemaArray, $formatter);

$renderer->addRenderer(
    new CustomPropertyRenderer(),
    new PropertyRenderer('my_custom_property', 'My super property')
);

foreach ($renderer as $role => $rows) {
    $output->writeln($rows);
}
```

### Store schema in a PHP file

```php
use Cycle\Schema\Renderer\SchemaToPhpRenderer;

$path = __DIR__. '/schema.php'

$renderer = new SchemaToPhpRenderer();

file_put_contents($path, $renderer->render($schemaArray));
```

The Renderer generates valid PHP code, in which constants from Cycle ORM classes are substituted
for better readability.
