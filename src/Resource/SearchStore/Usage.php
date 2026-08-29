<?php

namespace OneToMany\AI\Resource\SearchStore;

use function array_sum;
use function is_int;
use function max;

final readonly class Usage
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
        if (false === is_int($active)) {
            $active = (int) $active;
        }

        $this->active = max(0, $active);

        if (false === is_int($pending)) {
            $pending = (int) $pending;
        }

        $this->pending = max(0, $pending);

        if (false === is_int($failed)) {
            $failed = (int) $failed;
        }

        $this->failed = max(0, $failed);

        $total ??= array_sum([
            $this->active,
            $this->pending,
            $this->failed,
        ]);

        $this->total = max(0, (int) $total);
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
