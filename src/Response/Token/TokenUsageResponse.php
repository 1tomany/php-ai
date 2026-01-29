<?php

namespace OneToMany\AI\Response\Token;

use OneToMany\AI\Contract\Response\Token\TokenUsageResponseInterface;

final readonly class TokenUsageResponse implements TokenUsageResponseInterface
{
    /**
     * @param non-negative-int $promptTokens
     * @param non-negative-int $outputTokens
     */
    public function __construct(
        public int $promptTokens = 0,
        public int $outputTokens = 0,
    ) {
    }

    /**
     * @see OneToMany\AI\Contract\Response\Token\TokenUsageResponseInterface
     */
    public function getPromptTokens(): int
    {
        return $this->promptTokens;
    }

    /**
     * @see OneToMany\AI\Contract\Response\Token\TokenUsageResponseInterface
     */
    public function getOutputTokens(): int
    {
        return $this->outputTokens;
    }
}
