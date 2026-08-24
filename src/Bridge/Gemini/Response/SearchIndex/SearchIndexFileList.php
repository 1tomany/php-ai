<?php

namespace OneToMany\AI\Bridge\Gemini\Response\SearchIndex;

final readonly class SearchIndexFileList
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
