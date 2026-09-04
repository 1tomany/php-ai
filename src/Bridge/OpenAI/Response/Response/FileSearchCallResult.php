<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

use OneToMany\AI\Resource\Prompt\ToolResult\IndexSearchMatch;

final readonly class FileSearchCallResult
{
    /**
     * @param ?non-empty-string $file_id
     * @param ?non-empty-string $filename
     * @param ?non-empty-string $text
     */
    public function __construct(
        public ?string $file_id = null,
        public ?string $filename = null,
        public float $score = 0.0,
        public ?string $text = null,
    ) {
    }

    public function toResource(): IndexSearchMatch
    {
        return new IndexSearchMatch($this->file_id, $this->filename, $this->score, $this->text);
    }
}
