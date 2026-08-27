<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStore\Statistics;

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

    public function toResource(): SearchStore
    {
        return new SearchStore($this->id, $this->name, $this->description, $this->status, new Statistics($this->file_counts->total, $this->file_counts->completed, $this->file_counts->in_progress, $this->file_counts->failed, $this->file_counts->cancelled));
    }
}
