<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class ResponseOutputRefusal
{
    /**
     * @param 'refusal' $type
     */
    public function __construct(
        public string $type,
        public string $refusal,
    ) {
    }
}
