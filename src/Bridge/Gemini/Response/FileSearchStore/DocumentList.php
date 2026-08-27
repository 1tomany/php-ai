<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

final readonly class DocumentList
{
    /**
     * @param list<array<string, mixed>> $documents
     */
    public function __construct(
        public array $documents = [],
        public ?string $nextPageToken = null,
    ) {
    }
}
