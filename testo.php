<?php

declare(strict_types=1);

use Cycle\Testing\Config;

return Config::forPackage('cycle/schema-renderer', root: __DIR__)
    ->unit('tests/Unit')
    ->build();
