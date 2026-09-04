<?php

namespace OneToMany\AI\Tests\Resource\Prompt;

use OneToMany\AI\Resource\Prompt\Enum\ToolType;
use OneToMany\AI\Resource\Prompt\IndexSearchTool;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
#[Group('PromptTests')]
final class IndexSearchToolTest extends TestCase
{
    public function testUsesIndexSearchToolType(): void
    {
        $tool = new IndexSearchTool(['index_123']);

        $this->assertSame(ToolType::IndexSearch, $tool->getType());
    }

    public function testReturnsIndexIds(): void
    {
        $indexIds = ['index_123', 'index_456'];

        $this->assertSame($indexIds, new IndexSearchTool($indexIds)->getIndexIds());
    }
}
