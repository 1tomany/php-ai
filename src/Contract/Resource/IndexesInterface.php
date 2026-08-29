<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Model;
use OneToMany\AI\Resource\Index\Index;
use OneToMany\AI\Vendor;

interface IndexesInterface
{
    public IndexFilesInterface $files { get; }

    /**
     * @throws DomainException when the name is empty
     */
    public function create(string|Vendor $vendor, string $name, string|Model|null $model = null): Index;

    /**
     * @throws DomainException when the index ID is empty
     */
    public function read(string|Vendor $vendor, ?string $indexId): Index;

    /**
     * @throws DomainException when the index ID is empty
     */
    public function delete(string|Vendor $vendor, ?string $indexId): void;
}
