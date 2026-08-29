<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

use OneToMany\AI\Bridge\Gemini\Response\Batch\Operation;

final readonly class ImportFileOperation extends Operation
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        bool $done = false,
        public ?ImportFileStatus $error = null,
        public ?ImportFileResponse $response = null,
    ) {
        parent::__construct($name, $done);
    }
}
