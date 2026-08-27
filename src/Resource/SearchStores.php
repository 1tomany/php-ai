<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Contract\Resource\SearchStoreFilesInterface;
use OneToMany\AI\Contract\Resource\SearchStoresInterface;
use OneToMany\AI\Exception\EmptyIdException;
use OneToMany\AI\Exception\InvalidArgumentException;
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
        ?string $name,
        ?string $description = null,
    ): SearchStore {
        if ('' === $name = trim((string) $name)) {
            throw new InvalidArgumentException('The search store name cannot be empty.');
        }

        if (false === is_null($description)) {
            $description = trim($description);
        }

        return $this->providers->get(Vendor::create($vendor))->create($name, '' !== $description ? $description : null);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoresInterface
     */
    #[\Override]
    public function read(
        string|Vendor $vendor,
        ?string $searchStoreId,
    ): SearchStore {
        return $this->providers->get(Vendor::create($vendor))->read(EmptyIdException::validate($searchStoreId, 'search store'));
    }

    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoresInterface
     */
    #[\Override]
    public function delete(
        string|Vendor $vendor,
        ?string $searchStoreId,
    ): void {
        $this->providers->get(Vendor::create($vendor))->delete(EmptyIdException::validate($searchStoreId, 'search store'));
    }
}
