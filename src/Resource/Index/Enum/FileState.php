<?php

namespace OneToMany\AI\Resource\Index\Enum;

enum FileState
{
    case Pending;
    case Active;
    case Failed;

    /**
     * @phpstan-assert-if-true self::Pending $this
     */
    public function isPending(): bool
    {
        return self::Pending === $this;
    }

    /**
     * @phpstan-assert-if-true self::Active $this
     */
    public function isActive(): bool
    {
        return self::Active === $this;
    }

    /**
     * @phpstan-assert-if-true self::Failed $this
     */
    public function isFailed(): bool
    {
        return self::Failed === $this;
    }
}
