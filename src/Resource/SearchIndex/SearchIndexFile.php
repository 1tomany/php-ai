<?php

namespace OneToMany\AI\Resource\SearchIndex;

final readonly class SearchIndexFile
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $searchIndexId
     * @param non-empty-string $fileId
     * @param ?non-empty-string $status
     * @param array<string, string|int|float|bool> $metadata
     */
    public function __construct(
        public string $id,
        public string $searchIndexId,
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
    public function getSearchIndexId(): string
    {
        return $this->searchIndexId;
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
     * @return array<string, string|int|float|bool>
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
