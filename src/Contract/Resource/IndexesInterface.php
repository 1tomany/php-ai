<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\ModelVendor;
use OneToMany\AI\Resource\Index\Index;

interface IndexesInterface
{
    public IndexFilesInterface $files { get; }

    /**
     * @throws DomainException when the name is empty
     */
    public function create(string|ModelVendor $vendor, string $name, bool $multimodal = false): Index;

    /**
     * @throws DomainException when the index ID is empty
     */
    public function read(string|ModelVendor $vendor, ?string $indexId): Index;

    /**
     * @throws DomainException when the index ID is empty
     */
    public function delete(string|ModelVendor $vendor, ?string $indexId): void;
}
