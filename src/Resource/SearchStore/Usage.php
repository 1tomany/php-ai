<?php

namespace OneToMany\AI\Resource\SearchStore;

use function is_string;
use function max;

final readonly class Usage
{
    /**
     * @var non-negative-int
     */
    public int $bytes;

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
     * @param non-negative-int|numeric-string $bytes
     * @param non-negative-int|numeric-string $active
     * @param non-negative-int|numeric-string $pending
     * @param non-negative-int|numeric-string $failed
     * @param non-negative-int|numeric-string|null $total
     */
    public function __construct(
        int|string $bytes = 0,
        int|string $active = 0,
        int|string $pending = 0,
        int|string $failed = 0,
        int|string|null $total = null,
    ) {
        if (is_string($bytes)) {
            $bytes = (int) $bytes;
        }

        $this->bytes = max(0, $bytes);

        if (is_string($active)) {
            $active = (int) $active;
        }

        $this->active = max(0, $active);

        if (is_string($pending)) {
            $pending = (int) $pending;
        }

        $this->pending = max(0, $pending);

        if (is_string($failed)) {
            $failed = (int) $failed;
        }

        $this->failed = max(0, $failed);

        if (null === $total) {
            $total = (
                $this->active +
                $this->pending +
                $this->failed
            );
        }

        $this->total = max(0, (int) $total);
    }

    /**
     * @return non-negative-int
     */
    public function getBytes(): int
    {
        return $this->bytes;
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
