<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

final readonly class ImportFileResponse
{
    /**
     * @param ?non-empty-string $parent
     * @param ?non-empty-string $documentName
     */
    public function __construct(
        public ?string $parent = null,
        public ?string $documentName = null,
    ) {
    }
}
