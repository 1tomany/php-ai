<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\IndexProviderInterface;
use OneToMany\AI\Contract\Resource\IndexesInterface;
use OneToMany\AI\Contract\Resource\IndexFilesInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Model;
use OneToMany\AI\Resource\Index\Index;
use OneToMany\AI\Vendor;

use function sprintf;
use function trim;

/**
 * @extends AbstractResource<IndexProviderInterface>
 */
final readonly class Indexes extends AbstractResource implements IndexesInterface
{
    /**
     * @param iterable<IndexProviderInterface> $providers
     */
    public function __construct(
        iterable $providers,
        public IndexFilesInterface $files,
    ) {
        parent::__construct($providers);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\IndexesInterface
     */
    #[\Override]
    public function create(
        string|Vendor $vendor,
        string $name,
        string|Model|null $model = null,
    ): Index {
        if (!$vendor instanceof Vendor) {
            $vendor = Vendor::create($vendor);
        }

        if ('' === $name = trim((string) $name)) {
            throw new DomainException('The index name cannot be empty.');
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
     * @see OneToMany\AI\Contract\Resource\IndexesInterface
     */
    #[\Override]
    public function read(
        string|Vendor $vendor,
        ?string $indexId,
    ): Index {
        return $this->getProvider($vendor)->read(DomainException::validateId($indexId, 'index'));
    }

    /**
     * @see OneToMany\AI\Contract\Resource\IndexesInterface
     */
    #[\Override]
    public function delete(
        string|Vendor $vendor,
        ?string $indexId,
    ): void {
        $this->getProvider($vendor)->delete(DomainException::validateId($indexId, 'index'));
    }
}
