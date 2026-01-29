<?php

namespace OneToMany\AI\Contract\Response\Token;

interface TokenUsageResponseInterface
{
    /**
     * @var non-negative-int
     */
    public int $promptTokens { get; }

    /**
     * @var non-negative-int
     */
    public int $outputTokens { get; }
}
