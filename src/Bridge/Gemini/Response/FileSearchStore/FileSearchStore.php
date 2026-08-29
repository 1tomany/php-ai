<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStore\Usage;

use function max;

final class FileSearchStore
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $embeddingModel
     * @param ?non-empty-string $displayName
     * @param non-negative-int|numeric-string $activeDocumentsCount
     * @param non-negative-int|numeric-string $pendingDocumentsCount
     * @param non-negative-int|numeric-string $failedDocumentsCount
     */
    public function __construct(
        public readonly string $name,
        public readonly \DateTimeImmutable $createTime,
        public readonly \DateTimeImmutable $updateTime,
        public readonly string $embeddingModel,
        public readonly ?string $displayName = null,
        public readonly int|string $activeDocumentsCount = 0,
        public readonly int|string $pendingDocumentsCount = 0,
        public readonly int|string $failedDocumentsCount = 0,
        public readonly int|string $sizeBytes = 0,
    ) {
    }

    /**
     * @var non-empty-string
     */
    public string $label {
        get => $this->displayName ?? $this->name;
    }

    /**
     * @var non-negative-int
     */
    public int $bytes {
        get => max(0, (int) $this->sizeBytes);
    }

    public function toResource(): SearchStore
    {
        return new SearchStore($this->name, $this->label, $this->bytes, new Usage($this->activeDocumentsCount, $this->pendingDocumentsCount, $this->failedDocumentsCount));
    }
}
