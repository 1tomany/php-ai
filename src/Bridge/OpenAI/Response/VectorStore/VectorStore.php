<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum\VectorStoreStatus;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStore\Usage;

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
        $counts = $this->file_counts;

        $usage = new Usage(
            $this->usage_bytes,
            $counts->completed,
            $counts->in_progress,
            $counts->unavailable,
            $counts->total,
        );

        return new SearchStore($this->id, $this->name, $usage);
    }
}
