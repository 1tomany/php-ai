<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Contract\Resource\SearchStoreFilesInterface;
use OneToMany\AI\Contract\Resource\SearchStoresInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Vendor;

use function is_null;
use function trim;

/**
 * @extends AbstractResource<SearchStoreProviderInterface>
 */
final readonly class SearchStores extends AbstractResource implements SearchStoresInterface
{
    /**
     * @param iterable<SearchStoreProviderInterface> $providers
     */
    public function __construct(
        iterable $providers,
        public SearchStoreFilesInterface $files,
    ) {
        parent::__construct($providers);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoresInterface
     */
    #[\Override]
    public function create(
        string|Vendor $vendor,
        string $name,
        ?string $model = null,
    ): SearchStore {
        if ('' === $name = trim((string) $name)) {
            throw new DomainException('The search store name cannot be empty.');
        }

        if (null !== $model) {
            $model = trim($model);
        }

        return $this->getProvider($vendor)->create($name, '' !== $model ? $model : null);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoresInterface
     */
    #[\Override]
    public function read(
        string|Vendor $vendor,
        ?string $searchStoreId,
    ): SearchStore {
        return $this->getProvider($vendor)->read(DomainException::validateId($searchStoreId, 'search store'));
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoresInterface
     */
    #[\Override]
    public function delete(
        string|Vendor $vendor,
        ?string $searchStoreId,
    ): void {
        $this->getProvider($vendor)->delete(DomainException::validateId($searchStoreId, 'search store'));
    }
}
