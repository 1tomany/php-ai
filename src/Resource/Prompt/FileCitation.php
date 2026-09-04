<?php

namespace OneToMany\AI\Resource\Prompt;

final readonly class FileCitation
{
    /**
     * @param non-empty-string $fileId
     * @param non-empty-string $filename
     */
    public function __construct(
        public string $fileId,
        public string $filename,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getFileId(): string
    {
        return $this->fileId;
    }

    /**
     * @return non-empty-string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }
}
