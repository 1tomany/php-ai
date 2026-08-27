<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

final readonly class Document
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $state
     * @param list<array<string, mixed>> $customMetadata
     */
    public function __construct(
        public string $name,
        public string $state,
        public array $customMetadata = [],
        public string $displayName = '',
    ) {
    }
}
