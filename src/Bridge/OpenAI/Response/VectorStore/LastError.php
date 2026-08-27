<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum\LastErrorCode;

final readonly class LastError
{
    public function __construct(
        public LastErrorCode $code,
        public string $message,
    ) {
    }
}
