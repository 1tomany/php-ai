<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

final readonly class ImportFileStatus
{
    /**
     * @param non-negative-int $code
     * @param non-empty-string $message
     */
    public function __construct(
        public int $code,
        public string $message,
    ) {
    }
}
