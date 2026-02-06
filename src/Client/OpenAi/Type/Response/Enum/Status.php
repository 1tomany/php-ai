<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response\Enum;

enum Status: string
{
    case Cancelled = 'cancelled';
    case Completed = 'completed';
    case Failed = 'failed';
    case Incomplete = 'incomplete';
    case InProgress = 'in_progress';
    case Queued = 'queued';
}
