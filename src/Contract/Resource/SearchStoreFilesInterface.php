<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\Index\SearchStoreFile;
use OneToMany\AI\Vendor;

interface SearchStoreFilesInterface
{
    /**
     * @param ?array<string, scalar> $metadata
     *
     * @throws DomainException when the search store ID is empty
     * @throws DomainException when the file ID is empty
     */
    public function attach(string|Vendor $vendor, ?string $searchStoreId, ?string $fileId, ?array $metadata = null): SearchStoreFile;

    /**
     * @throws DomainException when the search store ID is empty
     * @throws DomainException when the search store file ID is empty
     */
    public function read(string|Vendor $vendor, ?string $searchStoreId, ?string $searchStoreFileId): SearchStoreFile;

    /**
     * @throws DomainException when the search store ID is empty
     * @throws DomainException when the search store file ID is empty
     */
    public function delete(string|Vendor $vendor, ?string $searchStoreId, ?string $searchStoreFileId): void;
}
