<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Enum;

enum DocumentState: string
{
    case Pending = 'STATE_PENDING';
    case Active = 'STATE_ACTIVE';
    case Failed = 'STATE_FAILED';
    case Unspecified = 'STATE_UNSPECIFIED';

    /**
     * @phpstan-assert-if-true self::Active $this
     */
    public function isActive(): bool
    {
        return self::Active === $this;
    }
}
