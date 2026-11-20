<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\MermaidRenderer;

use Cycle\ORM\Relation;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\MermaidRenderer\MermaidRenderer;
use Testo\Assert;
use Testo\Expect;
use Testo\Test;

#[Test]
final class MermaidRendererTest
{
    public function relationWithoutTargetOrSchemaIsSkipped(): void
    {
        $schema = $this->userWithRelations([
            'noSchema' => [Relation::TYPE => Relation::HAS_ONE, Relation::TARGET => 'user'],
            'noTarget' => [Relation::TYPE => Relation::HAS_ONE, Relation::SCHEMA => []],
        ]);

        Assert::same((new MermaidRenderer())->render($schema), <<<SCHEMA

            classDiagram
            class user {
                int id
            }


            SCHEMA);
    }

    public function unknownRelationTypeFails(): void
    {
        $schema = $this->userWithRelations([
            'friend' => [Relation::TYPE => 99, Relation::TARGET => 'user', Relation::SCHEMA => []],
        ]);

        Expect::exception(\ErrorException::class)->withMessage('Relation `99` not found.');

        (new MermaidRenderer())->render($schema);
    }

    private function userWithRelations(array $relations): array
    {
        return [
            'user' => [
                SchemaInterface::COLUMNS => ['id' => 'id'],
                SchemaInterface::TYPECAST => ['id' => 'int'],
                SchemaInterface::RELATIONS => $relations,
            ],
        ];
    }
}
