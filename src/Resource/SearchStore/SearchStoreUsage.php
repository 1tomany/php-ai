<?php

namespace OneToMany\AI\Resource\SearchStore;

use function is_int;
use function is_string;
use function max;

final readonly class SearchStoreUsage
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
     * @param non-negative-int|numeric-string $active
     * @param non-negative-int|numeric-string $pending
     * @param non-negative-int|numeric-string $failed
     * @param non-negative-int|numeric-string|null $total
     */
    public function __construct(
        int|string $active = 0,
        int|string $pending = 0,
        int|string $failed = 0,
        int|string|null $total = null,
    ) {
        $active = max(0, (int) $active);
        $pending = max(0, (int) $pending);
        $failed = max(0, (int) $failed);

        if (!is_int($total) && !is_string($total)) {
            $total = $active + $pending + $failed;
        }

        $this->active = $active;
        $this->pending = $pending;
        $this->failed = $failed;
        $this->total = $total;
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
    public function getPending(): int
    {
        return $this->pending;
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
