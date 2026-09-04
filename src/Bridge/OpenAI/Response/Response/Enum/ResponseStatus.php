<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response\Enum;

enum ResponseStatus: string
{
    case Completed = 'completed';
    case Failed = 'failed';
    case InProgress = 'in_progress';
    case Cancelled = 'cancelled';
    case Queued = 'queued';
    case Incomplete = 'incomplete';

    /**
     * @phpstan-assert-if-true self::Completed $this
     */
    public function isCompleted(): bool
    {
        return self::Completed === $this;
    }
}
