<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;
use OneToMany\AI\Vendor;

interface SearchStoreFilesInterface
{
    /**
     * @param array<string, scalar> $metadata
     *
     * @throws InvalidArgumentException when an ID or metadata key is empty
     * @throws InvalidArgumentException when a metadata value is not a scalar
     */
    public function attach(
        string|Vendor $vendor,
        ?string $searchStoreId,
        ?string $fileId,
        array $metadata = [],
        bool $force = false,
    ): SearchStoreFile;

    /**
     * @throws InvalidArgumentException when an ID is empty
     */
    public function read(
        string|Vendor $vendor,
        ?string $searchStoreId,
        ?string $searchStoreFileId,
    ): SearchStoreFile;

    /**
     * @throws InvalidArgumentException when an ID is empty
     */
    public function delete(
        string|Vendor $vendor,
        ?string $searchStoreId,
        ?string $searchStoreFileId,
    ): void;
}
