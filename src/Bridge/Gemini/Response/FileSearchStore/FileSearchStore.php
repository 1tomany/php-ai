<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

use OneToMany\AI\Resource\SearchStore\Counts;
use OneToMany\AI\Resource\SearchStore\SearchStore;

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
     * @var non-negative-int
     */
    public int $activeDocuments {
        get => max(0, (int) $this->activeDocumentsCount);
    }

    /**
     * @var non-negative-int
     */
    public int $pendingDocuments {
        get => max(0, (int) $this->pendingDocumentsCount);
    }

    /**
     * @var non-negative-int
     */
    public int $failedDocuments {
        get => max(0, (int) $this->failedDocumentsCount);
    }

    /**
     * @var non-negative-int
     */
    public int $totalDocuments {
        get => $this->activeDocuments + $this->pendingDocuments + $this->failedDocuments;
    }

    public function toResource(): SearchStore
    {
        return new SearchStore($this->name, $this->displayName ?? $this->name, new Counts($this->activeDocuments, $this->pendingDocuments, $this->failedDocuments, $this->totalDocuments));
    }
}
