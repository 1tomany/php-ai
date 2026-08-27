<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum;

enum VectorStoreFileStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Failed = 'failed';
}
