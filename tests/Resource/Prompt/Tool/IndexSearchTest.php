<?php

namespace OneToMany\AI\Tests\Resource\Prompt\Tool;

use OneToMany\AI\Resource\Prompt\Enum\ToolType;
use OneToMany\AI\Resource\Prompt\Tool\IndexSearch;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
#[Group('PromptTests')]
final class IndexSearchTest extends TestCase
{
    public function testUsesIndexSearchToolType(): void
    {
        $this->assertSame(ToolType::IndexSearch, new IndexSearch(['index_123'])->getType());
    }

    public function testReturnsIndexIds(): void
    {
        $indexIds = ['index_123', 'index_456'];

        $this->assertSame($indexIds, new IndexSearch($indexIds)->getIndexIds());
    }
}
