<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Contract\Resource\SearchStoreFilesInterface;
use OneToMany\AI\Contract\Resource\SearchStoresInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Model;
use OneToMany\AI\Resource\SearchStore\SearchStore;
use OneToMany\AI\Vendor;
use PhpParser\Node\Expr\BinaryOp\Mod;

use function sprintf;
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
        string|Model|null $model = null,
    ): SearchStore {
        if (!$vendor instanceof Vendor) {
            $vendor = Vendor::create($vendor);
        }

        if ('' === $name = trim((string) $name)) {
            throw new DomainException('The search store name cannot be empty.');
        }

        if (null !== $model) {
            if (!$model instanceof Model) {
                $model = Model::create($model);
            }

            if ($model->getVendor() !== $vendor) {
                throw new DomainException(sprintf('The model "%s" cannot be used with the vendor "%s".', $model->getId(), $vendor->getValue()));
            }
        }

        return $this->getProvider($vendor)->create($name, null !== $model ? Model::create($model)->getName() : null);
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
