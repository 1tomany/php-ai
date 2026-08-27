<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;
use OneToMany\AI\Vendor;

interface SearchStoreFilesInterface
{
    /**
     * @param ?array<string, scalar> $metadata
     *
     * @throws InvalidArgumentException when the search store ID is empty
     * @throws InvalidArgumentException when the file ID is empty
     */
    public function attach(string|Vendor $vendor, ?string $searchStoreId, ?string $fileId, ?array $metadata = null): SearchStoreFile;

    /**
     * @throws InvalidArgumentException when the search store ID is empty
     * @throws InvalidArgumentException when the search store file ID is empty
     */
    public function read(string|Vendor $vendor, ?string $searchStoreId, ?string $searchStoreFileId): SearchStoreFile;

    /**
     * @throws InvalidArgumentException when the search store ID is empty
     * @throws InvalidArgumentException when the search store file ID is empty
     */
    public function delete(string|Vendor $vendor, ?string $searchStoreId, ?string $searchStoreFileId): void;
}
