<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class ResponseOutputRefusal implements ContentInterface
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
