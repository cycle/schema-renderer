<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Schema;

use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityInterface;
use Cycle\Schema\Renderer\MermaidRenderer\Stringable;

interface SchemaInterface extends Stringable
{
    public function addEntity(EntityInterface $entity): void;
}
