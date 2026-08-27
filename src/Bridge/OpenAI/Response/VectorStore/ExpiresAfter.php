<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum\ExpiresAfterAnchor;

final readonly class ExpiresAfter
{
    /**
     * @param int<1, 365> $days
     */
    public function __construct(
        public ExpiresAfterAnchor $anchor,
        public int $days,
    ) {
    }
}
