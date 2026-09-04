<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class FileSearchCallResult
{
    /**
     * @param ?non-empty-string $file_id
     * @param ?non-empty-string $filename
     * @param ?non-empty-string $text
     */
    public function __construct(
        public ?string $file_id = null,
        public ?string $filename = null,
        public float $score = 0.0,
        public ?string $text = null,
    ) {
    }
}
