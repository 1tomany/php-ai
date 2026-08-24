<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\SearchIndex;

final readonly class SearchIndex
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $name
     * @param non-empty-string $status
     * @param ?non-empty-string $description
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $status,
        public FileCounts $file_counts,
        public ?string $description = null,
    ) {
    }
}
