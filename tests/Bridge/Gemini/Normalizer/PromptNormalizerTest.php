<?php

namespace OneToMany\AI\Tests\Bridge\Gemini\Normalizer;

use OneToMany\AI\Bridge\Gemini\Normalizer\PromptNormalizer;
use OneToMany\AI\Resource\Prompt\Prompt;
use OneToMany\AI\Resource\Prompt\Tool\IndexSearch;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('BridgeTests')]
#[Group('GeminiTests')]
final class PromptNormalizerTest extends TestCase
{
    public function testNormalizesIndexSearchTool(): void
    {
        $tool = new IndexSearch([
            'index_123', 'index_456',
        ]);

        $prompt = Prompt::create('gemini:gemini-pro', $tool);

        $this->assertCount(1, $prompt->getTools());
        $this->assertSame($tool, $prompt->getTools()[0]);

        $tools = [
            [
                'type' => 'file_search',
                'file_search_store_names' => [
                    'index_123', 'index_456',
                ],
            ],
        ];

        $payload = new PromptNormalizer()->normalize($prompt);

        $this->assertIsArray($payload['tools']);
        $this->assertCount(1, $payload['tools']);
        $this->assertSame($tools, $payload['tools']);
    }
}
