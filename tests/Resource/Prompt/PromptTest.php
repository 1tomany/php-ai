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
        $tool = new IndexSearch([
            'index_123', 'index_456',
        ]);

        $prompt = Prompt::create('openai:gpt-5.6', $tool);

        $this->assertSame([$tool], $prompt->getTools());
    }

    public function testAddToolReturnsPromptWithTool(): void
    {
        $tool = new IndexSearch([
            'index_123', 'index_456',
        ]);

        $prompt = Prompt::create('openai:gpt-5.6');
        $this->assertCount(0, $prompt->getTools());

        $prompt = $prompt->addTool($tool);

        $this->assertCount(1, $prompt->getTools());
        $this->assertSame([$tool], $prompt->getTools());
    }
}
