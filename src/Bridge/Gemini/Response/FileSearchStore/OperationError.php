<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

final readonly class OperationError
{
    /**
     * @param ?non-empty-string $message
     */
    public function __construct(
        public int|string|null $code = null,
        public ?string $message = null,
    ) {
    }
}
