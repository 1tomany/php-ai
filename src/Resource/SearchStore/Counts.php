<?php

namespace OneToMany\AI\Resource\SearchStore;

final readonly class Counts
{
    /**
     * @param non-negative-int $active
     * @param non-negative-int $pending
     * @param non-negative-int $failed
     * @param non-negative-int $total
     */
    public function __construct(
        public int $active = 0,
        public int $pending = 0,
        public int $failed = 0,
        public int $total = 0,
    ) {
    }
}
