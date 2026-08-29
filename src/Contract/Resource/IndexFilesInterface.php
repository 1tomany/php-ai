<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\Index\SearchStoreFile;
use OneToMany\AI\Vendor;

interface IndexFilesInterface
{
    /**
     * @param ?array<string, scalar> $metadata
     *
     * @throws DomainException when the index ID is empty
     * @throws DomainException when the file ID is empty
     */
    public function attach(string|Vendor $vendor, ?string $indexId, ?string $fileId, ?array $metadata = null): SearchStoreFile;

    /**
     * @throws DomainException when the index ID is empty
     * @throws DomainException when the index file ID is empty
     */
    public function read(string|Vendor $vendor, ?string $indexId, ?string $indexFileId): SearchStoreFile;

    /**
     * @throws DomainException when the index ID is empty
     * @throws DomainException when the index file ID is empty
     */
    public function delete(string|Vendor $vendor, ?string $indexId, ?string $indexFileId): void;
}
