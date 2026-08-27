<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

use OneToMany\AI\Resource\SearchStore\SearchStoreFile;

final readonly class VectorStoreFile
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $vector_store_id
     * @param non-empty-string $status
     * @param ?array<string, scalar> $attributes
     */
    public function __construct(
        public string $id,
        public string $vector_store_id,
        public string $status,
        public ?array $attributes = null,
    ) {
    }

    public function toResource(): SearchStoreFile
    {
        return new SearchStoreFile($this->id, $this->vector_store_id, $this->id, $this->status, $this->attributes ?? []);
    }
}
