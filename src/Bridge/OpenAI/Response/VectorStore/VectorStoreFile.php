<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum\VectorStoreFileStatus;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;

final readonly class VectorStoreFile
{
    /**
     * @param non-empty-string $id
     * @param 'vector_store.file' $object
     * @param non-negative-int $usage_bytes
     * @param non-negative-int $created_at
     * @param non-empty-string $vector_store_id
     * @param ?array<string, scalar> $attributes
     */
    public function __construct(
        public string $id,
        public string $object,
        public int $usage_bytes,
        public int $created_at,
        public string $vector_store_id,
        public VectorStoreFileStatus $status,
        public ?LastError $last_error,
        public ?array $attributes = null,
    ) {
    }

    public function toResource(): SearchStoreFile
    {
        return new SearchStoreFile($this->id);
    }
}
