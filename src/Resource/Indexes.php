<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\IndexProviderInterface;
use OneToMany\AI\Contract\Resource\IndexesInterface;
use OneToMany\AI\Contract\Resource\IndexFilesInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\Index\Index;
use OneToMany\AI\Vendor;

use function trim;

/**
 * @extends resource<IndexProviderInterface>
 */
final readonly class Indexes extends Resource implements IndexesInterface
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
        bool $multimodal = false,
    ): Index {
        if ('' === $name = trim((string) $name)) {
            throw new DomainException('The index name cannot be empty.');
        }

        return $this->getProvider($vendor)->create($name, $multimodal);
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
