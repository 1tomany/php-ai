<?php

namespace OneToMany\AI\Bridge\Gemini\Response\SearchIndex;

final readonly class SearchIndex
{
    /**
     * @param non-empty-string $name
     * @param non-negative-int|numeric-string $activeDocumentsCount
     * @param non-negative-int|numeric-string $pendingDocumentsCount
     * @param non-negative-int|numeric-string $failedDocumentsCount
     */
    public function __construct(
        public string $name,
        public string $displayName = '',
        public int|string $activeDocumentsCount = 0,
        public int|string $pendingDocumentsCount = 0,
        public int|string $failedDocumentsCount = 0,
    ) {
    }
}
