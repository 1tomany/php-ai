<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Contract\Resource\SearchStoreFilesInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\Index\SearchStoreFile;
use OneToMany\AI\Resource\Shared\Metadata;
use OneToMany\AI\Vendor;

/**
 * @extends AbstractResource<SearchStoreProviderInterface>
 */
final readonly class SearchStoreFiles extends AbstractResource implements SearchStoreFilesInterface
{
    /**
     * @see OneToMany\AI\Contract\Resource\SearchStoreFilesInterface
     */
    #[\Override]
    public function attach(
        string|Vendor $vendor,
        ?string $searchStoreId,
        ?string $fileId,
        ?array $metadata = null,
    ): SearchStoreFile {
        return $this->getProvider($vendor)->attachFile(DomainException::validateId($searchStoreId, 'search store'), DomainException::validateId($fileId, 'file'), new Metadata($metadata));
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
        return $this->getProvider($vendor)->readFile(DomainException::validateId($searchStoreId, 'search store'), DomainException::validateId($searchStoreFileId, 'search store file'));
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
        $this->getProvider($vendor)->deleteFile(DomainException::validateId($searchStoreId, 'search store'), DomainException::validateId($searchStoreFileId, 'search store file'));
    }
}
