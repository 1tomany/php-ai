<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Contract\Resource\SearchStoreFilesInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;
use OneToMany\AI\Vendor;

use function is_scalar;
use function is_string;
use function sprintf;
use function trim;

final readonly class SearchStoreFiles implements SearchStoreFilesInterface
{
    /**
     * @var Registry<SearchStoreProviderInterface>
     */
    private Registry $providers;

    /**
     * @param iterable<SearchStoreProviderInterface> $providers
     */
    public function __construct(
        iterable $providers,
    ) {
        $this->providers = new Registry($providers);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoreFilesInterface
     *
     * @param array<array-key, mixed> $metadata
     */
    #[\Override]
    public function attach(
        string|Vendor $vendor,
        ?string $searchStoreId,
        ?string $fileId,
        array $metadata = [],
        bool $force = false,
    ): SearchStoreFile {
        $searchStoreId = $this->validateId($searchStoreId, 'search store');
        $fileId = $this->validateId($fileId, 'file');

        foreach ($metadata as $key => $value) {
            if (!is_string($key) || '' === trim($key)) {
                throw new InvalidArgumentException('Search store file metadata keys must be non-empty strings.');
            }

            if (!is_scalar($value)) {
                throw new InvalidArgumentException(sprintf('The search store file metadata value for key "%s" must be a scalar type.', $key));
            }
        }

        return $this->providers->get(Vendor::create($vendor))->attachFile($searchStoreId, $fileId, $metadata, $force);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoreFilesInterface
     */
    #[\Override]
    public function read(
        string|Vendor $vendor,
        ?string $searchStoreId,
        ?string $searchStoreFileId,
    ): SearchStoreFile {
        $searchStoreId = $this->validateId($searchStoreId, 'search store');
        $searchStoreFileId = $this->validateId($searchStoreFileId, 'search store file');

        return $this->providers->get(Vendor::create($vendor))->readFile($searchStoreId, $searchStoreFileId);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoreFilesInterface
     */
    #[\Override]
    public function delete(
        string|Vendor $vendor,
        ?string $searchStoreId,
        ?string $searchStoreFileId,
    ): void {
        $searchStoreId = $this->validateId($searchStoreId, 'search store');
        $searchStoreFileId = $this->validateId($searchStoreFileId, 'search store file');

        $this->providers->get(Vendor::create($vendor))->deleteFile($searchStoreId, $searchStoreFileId);
    }

    /**
     * @return non-empty-string
     */
    private function validateId(?string $id, string $resource): string
    {
        if ('' === $id = trim((string) $id)) {
            throw new InvalidArgumentException(sprintf('The %s ID cannot be empty.', $resource));
        }

        return $id;
    }
}
