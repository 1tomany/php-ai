<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

final readonly class VectorStore
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
