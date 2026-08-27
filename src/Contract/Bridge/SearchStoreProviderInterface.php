<?php

namespace OneToMany\AI\Contract\Bridge;

use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;
use OneToMany\AI\Resource\Shared\Metadata;

interface SearchStoreProviderInterface extends ProviderInterface
{
    /**
     * @param non-empty-string $name
     * @param ?non-empty-string $description
     */
    public function create(string $name, ?string $description = null): SearchStore;

    /**
     * @param non-empty-string $searchStoreId
     */
    public function read(string $searchStoreId): SearchStore;

    /**
     * @param non-empty-string $searchStoreId
     */
    public function delete(string $searchStoreId): void;

    /**
     * @param non-empty-string $searchStoreId
     * @param non-empty-string $fileId
     */
    public function attachFile(string $searchStoreId, string $fileId, Metadata $metadata): SearchStoreFile;

    /**
     * @param non-empty-string $searchStoreId
     * @param non-empty-string $searchStoreFileId
     */
    public function readFile(string $searchStoreId, string $searchStoreFileId): SearchStoreFile;

    /**
     * @param non-empty-string $searchStoreId
     * @param non-empty-string $searchStoreFileId
     */
    public function deleteFile(string $searchStoreId, string $searchStoreFileId): void;
}
