<?php

namespace OneToMany\AI\Tests\Fixtures;

use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;
use OneToMany\AI\Vendor;

final class SearchStoreProvider implements SearchStoreProviderInterface
{
    /** @var ?array{string, ?string} */
    public ?array $createArguments = null;

    public ?string $readId = null;
    public ?string $deleteId = null;

    /** @var ?array{string, string, array<string, string|int|float|bool>, bool} */
    public ?array $attachFileArguments = null;

    /** @var ?array{string, string} */
    public ?array $readFileArguments = null;

    /** @var ?array{string, string} */
    public ?array $deleteFileArguments = null;

    public SearchStore $searchStore;
    public SearchStoreFile $searchStoreFile;

    public function __construct()
    {
        $this->searchStore = new SearchStore('vs_default', 'Default');
        $this->searchStoreFile = new SearchStoreFile('file_default', 'vs_default', 'file_default');
    }

    #[\Override]
    public static function getVendor(): Vendor
    {
        return Vendor::OpenAI;
    }

    #[\Override]
    public function create(string $name, ?string $description = null): SearchStore
    {
        $this->createArguments = [$name, $description];

        return $this->searchStore;
    }

    #[\Override]
    public function read(string $searchStoreId): SearchStore
    {
        $this->readId = $searchStoreId;

        return $this->searchStore;
    }

    #[\Override]
    public function delete(string $searchStoreId): void
    {
        $this->deleteId = $searchStoreId;
    }

    #[\Override]
    public function attachFile(
        string $searchStoreId,
        string $fileId,
        array $metadata = [],
        bool $force = false,
    ): SearchStoreFile {
        $this->attachFileArguments = [$searchStoreId, $fileId, $metadata, $force];

        return $this->searchStoreFile;
    }

    #[\Override]
    public function readFile(string $searchStoreId, string $searchStoreFileId): SearchStoreFile
    {
        $this->readFileArguments = [$searchStoreId, $searchStoreFileId];

        return $this->searchStoreFile;
    }

    #[\Override]
    public function deleteFile(string $searchStoreId, string $searchStoreFileId): void
    {
        $this->deleteFileArguments = [$searchStoreId, $searchStoreFileId];
    }
}
