<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum;

enum LastErrorCode: string
{
    case ServerError = 'server_error';
    case UnsupportedFile = 'unsupported_file';
    case InvalidFile = 'invalid_file';
}
