<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\MermaidRenderer\Relation;

use Cycle\Schema\Renderer\MermaidRenderer\Relation;

final class JoinedInheritanceRelation extends Relation
{
    public function __construct(string $parent, string $children, bool $isNullable)
    {
        parent::__construct($parent, $children, 'JTI', '--|>', $isNullable);
    }
}
