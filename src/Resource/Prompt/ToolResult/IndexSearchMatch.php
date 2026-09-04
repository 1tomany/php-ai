<?php

namespace OneToMany\AI\Resource\Prompt\ToolResult;

final readonly class IndexSearchMatch
{
    /**
     * @param ?non-empty-string $fileId
     * @param ?non-empty-string $filename
     * @param ?non-empty-string $text
     */
    public function __construct(
        public ?string $fileId = null,
        public ?string $filename = null,
        public float $score = 0.0,
        public ?string $text = null,
    ) {
    }

    /**
     * @return ?non-empty-string
     */
    public function getFileId(): ?string
    {
        return $this->fileId;
    }

    /**
     * @return ?non-empty-string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @return ?non-empty-string
     */
    public function getText(): ?string
    {
        return $this->text;
    }
}
