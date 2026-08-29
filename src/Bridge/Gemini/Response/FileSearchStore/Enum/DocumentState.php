<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Enum;

use OneToMany\AI\Resource\Index\Enum\FileState;

enum DocumentState: string
{
    case Pending = 'STATE_PENDING';
    case Active = 'STATE_ACTIVE';
    case Failed = 'STATE_FAILED';
    case Unspecified = 'STATE_UNSPECIFIED';

    public function getFileState(): FileState
    {
        $state = match ($this) {
            self::Pending => FileState::Pending,
            self::Active => FileState::Active,
            default => FileState::Failed,
        };

        return $state;
    }
}
