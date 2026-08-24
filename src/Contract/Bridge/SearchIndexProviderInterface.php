<?php

namespace OneToMany\AI\Contract\Bridge;

use OneToMany\AI\Resource\SearchIndex\SearchIndex;
use OneToMany\AI\Resource\SearchIndex\SearchIndexFile;

interface SearchIndexProviderInterface extends ProviderInterface
{
    /**
     * @param non-empty-string $name
     * @param ?non-empty-string $description
     */
    public function create(string $name, ?string $description = null): SearchIndex;

    /**
     * @param non-empty-string $searchIndexId
     * @param non-empty-string $fileId
     * @param array<string, string|int|float|bool> $metadata
     */
    public function attachFile(
        string $searchIndexId,
        string $fileId,
        array $metadata = [],
        bool $force = false,
    ): SearchIndexFile;

    /**
     * @param non-empty-string $searchIndexId
     * @param non-empty-string $searchIndexFileId
     */
    public function removeFile(string $searchIndexId, string $searchIndexFileId): void;

    /**
     * @param non-empty-string $searchIndexId
     */
    public function read(string $searchIndexId): SearchIndex;
}
