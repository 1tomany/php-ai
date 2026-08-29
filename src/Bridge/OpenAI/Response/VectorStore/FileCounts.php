<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

use function max;

final readonly class FileCounts
{
    /**
     * @var non-negative-int
     */
    public int $active;

    /**
     * @var non-negative-int
     */
    public int $pending;

    /**
     * @var non-negative-int
     */
    public int $failed;

    /**
     * @var non-negative-int
     */
    public int $total;

    /**
     * @param non-negative-int $completed
     * @param non-negative-int $in_progress
     * @param non-negative-int $failed
     * @param non-negative-int $cancelled
     * @param non-negative-int $total
     */
    public function __construct(
        int $completed = 0,
        int $in_progress = 0,
        int $failed = 0,
        int $cancelled = 0,
        int $total = 0,
    ) {
        $this->active = $completed;
        $this->pending = $in_progress;

        $this->failed = (
            max(0, $failed) +
            max(0, $cancelled)
        );

        $this->total = $total;
    }
}
