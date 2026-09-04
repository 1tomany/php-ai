<?php

namespace OneToMany\AI\Tests\Resource\Prompt;

use OneToMany\AI\Resource\Prompt\Prompt;
use OneToMany\AI\Resource\Prompt\Tool\IndexSearch;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
#[Group('PromptTests')]
final class PromptTest extends TestCase
{
    public function testCreateAcceptsTools(): void
    {
        $tool = new IndexSearch(['index_123']);
        $prompt = Prompt::create('openai:gpt-5.6', 'Search the index.', $tool);

        $this->assertSame([$tool], $prompt->getTools());
    }

    public function testAddToolReturnsPromptWithTool(): void
    {
        $tool = new IndexSearch(['index_123']);
        $prompt = Prompt::create('openai:gpt-5.6', 'Search the index.');
        $promptWithTool = $prompt->addTool($tool);

        $this->assertNotSame($prompt, $promptWithTool);
        $this->assertSame([], $prompt->getTools());
        $this->assertSame([$tool], $promptWithTool->getTools());
    }
}
