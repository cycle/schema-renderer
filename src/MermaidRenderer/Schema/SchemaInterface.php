<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Schema;

use Cycle\Schema\Renderer\MermaidRenderer\Entity\EntityInterface;

interface SchemaInterface
{
    public function addEntity(EntityInterface $entity): void;
}
