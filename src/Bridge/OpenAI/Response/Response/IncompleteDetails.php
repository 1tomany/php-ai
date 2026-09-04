<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class IncompleteDetails
{
    /**
     * @param non-empty-string $reason
     */
    public function __construct(
        public string $reason,
    ) {
    }
}
