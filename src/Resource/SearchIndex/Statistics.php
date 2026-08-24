<?php

namespace OneToMany\AI\Resource\SearchIndex;

final readonly class Statistics
{
    /**
     * @param non-negative-int $total
     * @param non-negative-int $completed
     * @param non-negative-int $inProgress
     * @param non-negative-int $failed
     * @param non-negative-int $cancelled
     */
    public function __construct(
        public int $total = 0,
        public int $completed = 0,
        public int $inProgress = 0,
        public int $failed = 0,
        public int $cancelled = 0,
    ) {
    }
}
