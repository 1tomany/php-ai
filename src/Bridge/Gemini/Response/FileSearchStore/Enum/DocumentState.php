<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Enum;

enum DocumentState: string
{
    case Pending = 'STATE_PENDING';
    case Active = 'STATE_ACTIVE';
    case Failed = 'STATE_FAILED';
    case Unspecified = 'STATE_UNSPECIFIED';
}
