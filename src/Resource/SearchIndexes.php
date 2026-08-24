<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\SearchIndexProviderInterface;
use OneToMany\AI\Contract\Resource\SearchIndexesInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Resource\SearchIndex\SearchIndex;
use OneToMany\AI\Resource\SearchIndex\SearchIndexFile;
use OneToMany\AI\Vendor;

use function is_scalar;
use function is_string;
use function sprintf;
use function trim;

final readonly class SearchIndexes implements SearchIndexesInterface
{
    /**
     * @var Registry<SearchIndexProviderInterface>
     */
    private Registry $providers;

    /**
     * @param iterable<SearchIndexProviderInterface> $providers
     */
    public function __construct(
        iterable $providers,
    ) {
        $this->providers = new Registry($providers);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchIndexesInterface
     */
    #[\Override]
    public function create(string|Vendor $vendor, ?string $name, ?string $description = null): SearchIndex
    {
        if ('' === $name = trim((string) $name)) {
            throw new InvalidArgumentException('The search index name cannot be empty.');
        }

        if (null !== $description) {
            $description = trim($description);
        }

        return $this->providers->get(Vendor::create($vendor))->create($name, '' !== $description ? $description : null);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchIndexesInterface
     */
    #[\Override]
    public function read(string|Vendor $vendor, ?string $searchIndexId): SearchIndex
    {
        if ('' === $searchIndexId = trim((string) $searchIndexId)) {
            throw new InvalidArgumentException('The search index ID cannot be empty.');
        }

        return $this->providers->get(Vendor::create($vendor))->read($searchIndexId);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchIndexesInterface
     *
     * @param array<array-key, mixed> $metadata
     */
    #[\Override]
    public function attachFile(
        string|Vendor $vendor,
        ?string $searchIndexId,
        ?string $fileId,
        array $metadata = [],
        bool $force = false,
    ): SearchIndexFile {
        if ('' === $searchIndexId = trim((string) $searchIndexId)) {
            throw new InvalidArgumentException('The search index ID cannot be empty.');
        }

        if ('' === $fileId = trim((string) $fileId)) {
            throw new InvalidArgumentException('The file ID cannot be empty.');
        }

        foreach ($metadata as $key => $value) {
            if (!is_string($key) || '' === trim($key)) {
                throw new InvalidArgumentException('Search index file metadata keys must be non-empty strings.');
            }

            if (!is_scalar($value)) {
                throw new InvalidArgumentException(sprintf('The search index file metadata value for key "%s" must be a scalar type.', $key));
            }
        }

        return $this->providers->get(Vendor::create($vendor))->attachFile($searchIndexId, $fileId, $metadata, $force);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchIndexesInterface
     */
    #[\Override]
    public function removeFile(
        string|Vendor $vendor,
        ?string $searchIndexId,
        ?string $searchIndexFileId,
    ): void {
        if ('' === $searchIndexId = trim((string) $searchIndexId)) {
            throw new InvalidArgumentException('The search index ID cannot be empty.');
        }

        if ('' === $searchIndexFileId = trim((string) $searchIndexFileId)) {
            throw new InvalidArgumentException('The search index file ID cannot be empty.');
        }

        $this->providers->get(Vendor::create($vendor))->removeFile($searchIndexId, $searchIndexFileId);
    }
}
