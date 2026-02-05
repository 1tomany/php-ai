<?php

namespace OneToMany\AI\Client\Gemini\Type;

use function array_filter;
use function explode;
use function implode;
use function rtrim;
use function trim;

final readonly class ErrorType
{
    public string $message;

    public function __construct(string $message, public int $code = 500)
    {
        $this->message = trim(implode(' ', array_filter(explode(' ', $message))));
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Removes any trailing periods so the message can be
     * concatenated with the message from another exception.
     */
    public function getInlineMessage(): string
    {
        return rtrim($this->message, '.');
    }
}
