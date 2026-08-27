<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Contract\Resource\SearchStoreFilesInterface;
use OneToMany\AI\Contract\Resource\SearchStoresInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Vendor;

use function trim;

final readonly class SearchStores implements SearchStoresInterface
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
        public SearchStoreFilesInterface $files,
    ) {
        $this->providers = new Registry($providers);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoresInterface
     */
    #[\Override]
    public function create(string|Vendor $vendor, ?string $name, ?string $description = null): SearchStore
    {
        if ('' === $name = trim((string) $name)) {
            throw new InvalidArgumentException('The search store name cannot be empty.');
        }

        if (null !== $description) {
            $description = trim($description);
        }

        return $this->providers->get(Vendor::create($vendor))->create($name, '' !== $description ? $description : null);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoresInterface
     */
    #[\Override]
    public function read(string|Vendor $vendor, ?string $searchStoreId): SearchStore
    {
        $searchStoreId = $this->validateId($searchStoreId);

        return $this->providers->get(Vendor::create($vendor))->read($searchStoreId);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoresInterface
     */
    #[\Override]
    public function delete(string|Vendor $vendor, ?string $searchStoreId): void
    {
        $searchStoreId = $this->validateId($searchStoreId);

        $this->providers->get(Vendor::create($vendor))->delete($searchStoreId);
    }

    /**
     * @return non-empty-string
     */
    private function validateId(?string $searchStoreId): string
    {
        if ('' === $searchStoreId = trim((string) $searchStoreId)) {
            throw new InvalidArgumentException('The search store ID cannot be empty.');
        }

        return $searchStoreId;
    }
}
