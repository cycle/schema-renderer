<?php

declare(strict_types=1);

namespace Cycle\Schema\Renderer\Tests\Unit\OutputSchemaRenderer;

use Cycle\ORM\SchemaInterface;
use Cycle\Schema\Renderer\ConstantsInterface;
use Cycle\Schema\Renderer\OutputSchemaRenderer;
use Testo\Assert;
use Testo\Test;

#[Test]
final class PropertiesTest
{
    public function testRepositoryAndScopeAreRendered(): void
    {
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT);

        $result = $renderer->render([
            'user' => [
                SchemaInterface::ROLE => 'user',
                SchemaInterface::SOURCE => 'App\UserSource',
                SchemaInterface::REPOSITORY => 'App\UserRepository',
                SchemaInterface::SCOPE => 'App\UserScope',
            ],
        ]);

        Assert::string($result)
            ->contains('   Repository: App\UserRepository')
            ->contains('        Scope: App\UserScope');
    }

    public function testCustomConstantsNameSchemaProperties(): void
    {
        $constants = new class implements ConstantsInterface {
            public function all(): array
            {
                return ['ROLE' => 0, 'REPOSITORY' => 100];
            }
        };
        $renderer = new OutputSchemaRenderer(OutputSchemaRenderer::FORMAT_PLAIN_TEXT, $constants);

        $result = $renderer->render([
            'user' => [
                SchemaInterface::ROLE => 'user',
                100 => 'App\UserRepository',
            ],
        ]);

        Assert::string($result)
            ->contains('   Repository: App\UserRepository')
            ->notContains('Custom props');
    }
}
