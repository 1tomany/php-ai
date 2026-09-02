<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

use OneToMany\AI\Resource\Index\Index;
use OneToMany\AI\Resource\Index\Usage;

final readonly class FileSearchStore
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $createTime
     * @param non-empty-string $updateTime
     * @param non-empty-string $embeddingModel
     * @param ?non-empty-string $displayName
     * @param non-negative-int|numeric-string $sizeBytes
     * @param non-negative-int|numeric-string $activeDocumentsCount
     * @param non-negative-int|numeric-string $pendingDocumentsCount
     * @param non-negative-int|numeric-string $failedDocumentsCount
     */
    public function __construct(
        public string $name,
        public string $createTime,
        public string $updateTime,
        public string $embeddingModel,
        public ?string $displayName = null,
        public int|string $sizeBytes = 0,
        public int|string $activeDocumentsCount = 0,
        public int|string $pendingDocumentsCount = 0,
        public int|string $failedDocumentsCount = 0,
    ) {
    }

    public function toResource(): Index
    {
        return new Index($this->name, $this->displayName, $this->embeddingModel, new Usage($this->sizeBytes, $this->activeDocumentsCount, $this->pendingDocumentsCount, $this->failedDocumentsCount));
    }
}
