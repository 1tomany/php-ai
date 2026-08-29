<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

use OneToMany\AI\Resource\SearchStore\Usage;

final class FileCounts
{
    /**
     * @param non-negative-int $completed
     * @param non-negative-int $in_progress
     * @param non-negative-int $failed
     * @param non-negative-int $cancelled
     * @param non-negative-int $total
     */
    public function __construct(
        public int $completed = 0,
        public int $in_progress = 0,
        public int $failed = 0,
        public int $cancelled = 0,
        public int $total = 0,
    ) {
    }

    public function toResource(): Usage
    {
        return new Usage($this->completed, $this->in_progress, $this->failed + $this->cancelled, $this->total);
    }
}
