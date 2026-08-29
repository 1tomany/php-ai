<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStore\SearchStoreUsage;

use function max;

final readonly class FileSearchStore
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
        public string $name,
        public \DateTimeImmutable $createTime,
        public \DateTimeImmutable $updateTime,
        public string $embeddingModel,
        public ?string $displayName = null,
        public int|string $activeDocumentsCount = 0,
        public int|string $pendingDocumentsCount = 0,
        public int|string $failedDocumentsCount = 0,
        public int|string $sizeBytes = 0,
    ) {
    }

    public function toResource(): SearchStore
    {
        return new SearchStore($this->name, $this->getName(), $this->getTotalBytes(), new SearchStoreUsage($this->activeDocumentsCount, $this->pendingDocumentsCount, $this->failedDocumentsCount));
    }

    /**
     * @return non-empty-string
     */
    private function getName(): string
    {
        return $this->displayName ?? $this->name;
    }

    /**
     * @return non-negative-int
     */
    private function getTotalBytes(): int
    {
        return max(0, (int) $this->sizeBytes);
    }
}
