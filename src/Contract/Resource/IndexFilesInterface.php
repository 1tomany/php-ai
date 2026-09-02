<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\Index\IndexFile;
use OneToMany\AI\ModelVendor;

interface IndexFilesInterface
{
    /**
     * @param ?array<string, scalar> $metadata
     *
     * @throws DomainException when the index ID is empty
     * @throws DomainException when the file ID is empty
     */
    public function attach(string|ModelVendor $vendor, ?string $indexId, ?string $fileId, ?array $metadata = null): IndexFile;

    /**
     * @throws DomainException when the index ID is empty
     * @throws DomainException when the index file ID is empty
     */
    public function read(string|ModelVendor $vendor, ?string $indexId, ?string $indexFileId): IndexFile;

    /**
     * @throws DomainException when the index ID is empty
     * @throws DomainException when the index file ID is empty
     */
    public function delete(string|ModelVendor $vendor, ?string $indexId, ?string $indexFileId): void;
}
