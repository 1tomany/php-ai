<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

use function str_replace;

final readonly class Operation
{
    /**
     * @var non-empty-string
     */
    public string $id;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
        public bool $done = false,
        public ?ImportFileResponse $response = null,
        public ?OperationError $error = null,
    ) {
        $this->id = str_replace('/operations/', '/documents/', $this->name);
    }
}
