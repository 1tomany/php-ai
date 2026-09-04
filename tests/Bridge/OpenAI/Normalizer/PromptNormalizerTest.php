<?php

namespace OneToMany\AI\Tests\Bridge\OpenAI\Normalizer;

use OneToMany\AI\Bridge\OpenAI\Normalizer\PromptNormalizer;
use OneToMany\AI\Resource\Prompt\IndexSearchTool;
use OneToMany\AI\Resource\Prompt\Prompt;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('BridgeTests')]
#[Group('OpenAITests')]
final class PromptNormalizerTest extends TestCase
{
    public function testNormalizesIndexSearchTool(): void
    {
        $prompt = Prompt::create(
            'openai:gpt-5.6',
            'Search the indexes.',
            new IndexSearchTool(['index_123', 'index_456']),
        );

        $payload = new PromptNormalizer()->normalize($prompt);

        $this->assertSame([
            [
                'type' => 'file_search',
                'vector_store_ids' => ['index_123', 'index_456'],
            ],
        ], $payload['tools']);
    }
}
