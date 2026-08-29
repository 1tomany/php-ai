<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\IndexProviderInterface;
use OneToMany\AI\Contract\Resource\IndexFilesInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\Index\IndexFile;
use OneToMany\AI\Resource\Shared\Metadata;
use OneToMany\AI\Vendor;

/**
 * @extends AbstractResource<IndexProviderInterface>
 */
final readonly class IndexFiles extends AbstractResource implements IndexFilesInterface
{
    /**
     * @see OneToMany\AI\Contract\Resource\IndexFilesInterface
     */
    #[\Override]
    public function attach(
        string|Vendor $vendor,
        ?string $indexId,
        ?string $fileId,
        ?array $metadata = null,
    ): IndexFile {
        return $this->getProvider($vendor)->attachFile(DomainException::validateId($indexId, 'index'), DomainException::validateId($fileId, 'file'), new Metadata($metadata));
    }

    /**
     * @see OneToMany\AI\Contract\Resource\IndexFilesInterface
     */
    #[\Override]
    public function read(
        string|Vendor $vendor,
        ?string $indexId,
        ?string $indexFileId,
    ): IndexFile {
        return $this->getProvider($vendor)->readFile(DomainException::validateId($indexId, 'index'), DomainException::validateId($indexFileId, 'index file'));
    }

    /**
     * @see OneToMany\AI\Contract\Resource\IndexFilesInterface
     */
    #[\Override]
    public function delete(
        string|Vendor $vendor,
        ?string $indexId,
        ?string $indexFileId,
    ): void {
        $this->getProvider($vendor)->deleteFile(DomainException::validateId($indexId, 'index'), DomainException::validateId($indexFileId, 'index file'));
    }
}
