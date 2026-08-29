<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum\VectorStoreStatus;
use OneToMany\AI\Resource\SearchStore\Counts;
use OneToMany\AI\Resource\SearchStore\SearchStore;

final readonly class VectorStore
{
    /**
     * @param non-empty-string $id
     * @param 'vector_store' $object
     * @param non-negative-int $created_at
     * @param non-empty-string $name
     * @param ?non-empty-string $description
     * @param non-negative-int $usage_bytes
     * @param ?non-negative-int $expires_at
     * @param ?non-negative-int $last_active_at
     */
    public function __construct(
        public string $id,
        public string $object,
        public int $created_at,
        public string $name,
        public ?string $description,
        public int $usage_bytes,
        public FileCounts $file_counts,
        public VectorStoreStatus $status,
        public ?ExpiresAfter $expires_after,
        public ?int $expires_at,
        public ?int $last_active_at,
    ) {
    }

    public function toResource(): SearchStore
    {
        return new SearchStore($this->id, $this->name, $this->usage_bytes, new Counts($this->file_counts->in_progress, $this->file_counts->completed, $this->file_counts->failed + $this->file_counts->cancelled, $this->file_counts->total));
    }
}
