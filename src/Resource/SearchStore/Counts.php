<?php

namespace OneToMany\AI\Resource\SearchStore;

final readonly class Counts
{
    /**
     * @param non-negative-int $pending
     * @param non-negative-int $active
     * @param non-negative-int $failed
     * @param non-negative-int $total
     */
    public function __construct(
        public int $pending = 0,
        public int $active = 0,
        public int $failed = 0,
        public int $total = 0,
    ) {
    }

    /**
     * @return non-negative-int
     */
    public function getPending(): int
    {
        return $this->pending;
    }

    /**
     * @return non-negative-int
     */
    public function getActive(): int
    {
        return $this->active;
    }

    /**
     * @return non-negative-int
     */
    public function getFailed(): int
    {
        return $this->failed;
    }

    /**
     * @return non-negative-int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
}
