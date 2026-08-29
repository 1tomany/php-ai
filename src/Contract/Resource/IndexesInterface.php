<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Model;
use OneToMany\AI\Resource\Index\SearchStore;
use OneToMany\AI\Vendor;

interface IndexesInterface
{
    public SearchStoreFilesInterface $files { get; }

    /**
     * @throws DomainException when the name is empty
     */
    public function create(string|Vendor $vendor, string $name, string|Model|null $model = null): SearchStore;

    /**
     * @throws DomainException when the search store ID is empty
     */
    public function read(string|Vendor $vendor, ?string $searchStoreId): SearchStore;

    /**
     * @throws DomainException when the search store ID is empty
     */
    public function delete(string|Vendor $vendor, ?string $searchStoreId): void;
}
