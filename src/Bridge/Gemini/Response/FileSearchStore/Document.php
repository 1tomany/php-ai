<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

use OneToMany\AI\Bridge\Gemini\Response\FileSearchStore\Enum\DocumentState;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;

use function max;

final readonly class Document
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $displayName
     * @param non-negative-int|numeric-string $sizeBytes
     * @param non-empty-string $mimeType
     */
    public function __construct(
        public string $name,
        public string $displayName,
        public \DateTimeImmutable $createTime,
        public \DateTimeImmutable $updateTime,
        public DocumentState $state,
        public int|string $sizeBytes,
        public string $mimeType,
    ) {
    }

    public function toResource(): SearchStoreFile
    {
        return new SearchStoreFile($this->name, $this->state->getFileState(), max(0, (int) $this->sizeBytes));
    }
}
