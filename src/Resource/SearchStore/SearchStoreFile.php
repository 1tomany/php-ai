<?php

namespace OneToMany\AI\Resource\SearchStore;

final readonly class SearchStoreFile
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $searchStoreId
     * @param non-empty-string $fileId
     * @param ?non-empty-string $status
     * @param array<string, scalar> $metadata
     */
    public function __construct(
        public string $id,
        public string $searchStoreId,
        public string $fileId,
        public ?string $status = null,
        public array $metadata = [],
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return non-empty-string
     */
    public function getSearchStoreId(): string
    {
        return $this->searchStoreId;
    }

    /**
     * @return non-empty-string
     */
    public function getFileId(): string
    {
        return $this->fileId;
    }

    /**
     * @return ?non-empty-string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return array<string, scalar>
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
