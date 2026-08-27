<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum;

enum VectorStoreStatus: string
{
    case Expired = 'expired';
    case InProgress = 'in_progress';
    case Completed = 'completed';
}
