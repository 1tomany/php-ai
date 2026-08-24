<?php

namespace OneToMany\AI\Bridge\Gemini\Response\SearchIndex;

final readonly class Operation
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
        public bool $done = false,
        public ?ImportFileResponse $response = null,
        public ?OperationError $error = null,
    ) {
    }
}
