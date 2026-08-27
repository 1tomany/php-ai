<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Vendor;

interface SearchStoresInterface
{
    public SearchStoreFilesInterface $files { get; }

    /**
     * @throws InvalidArgumentException when the name is empty
     */
    public function create(string|Vendor $vendor, ?string $name, ?string $description = null, ?string $embeddingModel = null): SearchStore;

    /**
     * @throws InvalidArgumentException when the search store ID is empty
     */
    public function read(string|Vendor $vendor, ?string $searchStoreId): SearchStore;

    /**
     * @throws InvalidArgumentException when the search store ID is empty
     */
    public function delete(string|Vendor $vendor, ?string $searchStoreId): void;
}
