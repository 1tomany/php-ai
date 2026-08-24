<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Resource\SearchIndex\SearchIndex;
use OneToMany\AI\Resource\SearchIndex\SearchIndexFile;
use OneToMany\AI\Vendor;

interface SearchIndexesInterface
{
    /**
     * @throws InvalidArgumentException when the name is empty
     */
    public function create(string|Vendor $vendor, ?string $name, ?string $description = null): SearchIndex;

    /**
     * @throws InvalidArgumentException when the search index ID is empty
     */
    public function read(string|Vendor $vendor, ?string $searchIndexId): SearchIndex;

    /**
     * @param array<string, scalar> $metadata
     *
     * @throws InvalidArgumentException when an ID or metadata key is empty
     * @throws InvalidArgumentException when a metadata value is not a scalar
     */
    public function attachFile(
        string|Vendor $vendor,
        ?string $searchIndexId,
        ?string $fileId,
        array $metadata = [],
        bool $force = false,
    ): SearchIndexFile;

    /**
     * @throws InvalidArgumentException when an ID is empty
     */
    public function removeFile(
        string|Vendor $vendor,
        ?string $searchIndexId,
        ?string $searchIndexFileId,
    ): void;
}
