<?php

namespace OneToMany\AI\Client\OpenAi\Type\Error;

use function rtrim;

final readonly class ErrorType
{
    /**
     * @param ?non-empty-string $type
     * @param ?non-empty-string $param
     * @param ?non-empty-string $code
     */
    public function __construct(
        public string $message,
        public ?string $type = null,
        public ?string $param = null,
        public ?string $code = null,
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getInlineMessage(): string
    {
        return rtrim($this->getMessage(), '.');
    }
}
