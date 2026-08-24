<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\SearchIndex;

final readonly class FileCounts
{
    /**
     * @param non-negative-int $total
     * @param non-negative-int $completed
     * @param non-negative-int $in_progress
     * @param non-negative-int $failed
     * @param non-negative-int $cancelled
     */
    public function __construct(
        public int $total = 0,
        public int $completed = 0,
        public int $in_progress = 0,
        public int $failed = 0,
        public int $cancelled = 0,
    ) {
    }
}
