<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum;

use OneToMany\AI\Resource\Index\Enum\FileState;

enum VectorStoreFileStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Failed = 'failed';

    public function getFileState(): FileState
    {
        $state = match ($this) {
            self::InProgress => FileState::Pending,
            self::Completed => FileState::Active,
            default => FileState::Failed,
        };

        return $state;
    }
}
