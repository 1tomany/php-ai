<?php

namespace OneToMany\AI\Bridge\Meta\Response\Response;

use OneToMany\AI\Resource\Prompt\Usage;

final readonly class ResponseUsage
{
    /**
     * @param non-negative-int $input_tokens
     * @param array{cached_tokens?: non-negative-int} $input_tokens_details
     * @param non-negative-int $output_tokens
     * @param array{reasoning_tokens?: non-negative-int} $output_tokens_details
     * @param non-negative-int $total_tokens
     */
    public function __construct(
        public int $input_tokens,
        public array $input_tokens_details,
        public int $output_tokens,
        public array $output_tokens_details,
        public int $total_tokens,
    ) {
    }

    public function toResource(): Usage
    {
        return new Usage(
            inputTokens: $this->input_tokens,
            cachedInputTokens: $this->input_tokens_details['cached_tokens'] ?? 0,
            reasoningTokens: $this->output_tokens_details['reasoning_tokens'] ?? 0,
            outputTokens: $this->output_tokens,
            totalTokens: $this->total_tokens,
        );
    }
}
