<?php

namespace OneToMany\AI\Tests\Bridge\Gemini\Normalizer;

use OneToMany\AI\Bridge\Gemini\Normalizer\PromptNormalizer;
use OneToMany\AI\Resource\Prompt\IndexSearchTool;
use OneToMany\AI\Resource\Prompt\Prompt;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('BridgeTests')]
#[Group('GeminiTests')]
final class PromptNormalizerTest extends TestCase
{
    public function testNormalizesIndexSearchTool(): void
    {
        $prompt = Prompt::create(
            'gemini:gemini-3.7-flash',
            'Search the indexes.',
            new IndexSearchTool(['index_123', 'index_456']),
        );

        $payload = new PromptNormalizer()->normalize($prompt);

        $this->assertSame([
            [
                'type' => 'file_search',
                'file_search_store_names' => ['index_123', 'index_456'],
            ],
        ], $payload['tools']);
    }
}
