<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\SearchStoreProviderInterface;
use OneToMany\AI\Contract\Resource\SearchStoreFilesInterface;
use OneToMany\AI\Exception\EmptyIdException;
use OneToMany\AI\Resource\SearchStore\SearchStoreFile;
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
        return $this->getProvider($vendor)->attachFile(EmptyIdException::validate($searchStoreId, 'search store'), EmptyIdException::validate($fileId, 'file'), $metadata);
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
        return $this->getProvider($vendor)->readFile(EmptyIdException::validate($searchStoreId, 'search store'), EmptyIdException::validate($searchStoreFileId, 'search store file'));
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
        $this->getProvider($vendor)->deleteFile(EmptyIdException::validate($searchStoreId, 'search store'), EmptyIdException::validate($searchStoreFileId, 'search store file'));
    }
}
