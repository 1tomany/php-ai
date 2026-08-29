<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

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
        public readonly int $completed = 0,
        public readonly int $in_progress = 0,
        public readonly int $failed = 0,
        public readonly int $cancelled = 0,
        public readonly int $total = 0,
    ) {
    }

    /**
     * @var non-negative-int
     */
    public int $unavailable {
        get => $this->failed + $this->cancelled;
    }
}
