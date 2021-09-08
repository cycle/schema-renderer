# CycleORM Schema renderer

This package may be used to render schema roles in a terminal or generate php representation for CycleORM schema.

### Render schema in a terminal

```php
$output = new \Symfony\Component\Console\Output\ConsoleOutput();

$renderer = new \Cycle\Schema\Renderer\ConsoleOutputRenderer();

foreach ($renderer->render($orm->getSchema(), 'user', 'profile', ...) as $line) {
    $output->writeln($line);
}
```

### Store schema in a PHP file

```php
$path = __DIR__. '/schema.php'

$renderer = new \Cycle\Schema\Renderer\SchemaToPhpFileRenderer();
file_put_contents($path, $renderer->render($orm->getSchema()));
```