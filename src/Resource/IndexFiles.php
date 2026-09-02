<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\IndexProviderInterface;
use OneToMany\AI\Contract\Resource\IndexFilesInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\Index\IndexFile;
use OneToMany\AI\Resource\Shared\Metadata;
use OneToMany\AI\ModelVendor;

/**
 * @extends Resources<IndexProviderInterface>
 */
final readonly class IndexFiles extends Resources implements IndexFilesInterface
{
    /**
     * @see OneToMany\AI\Contract\Resource\IndexFilesInterface
     */
    #[\Override]
    public function attach(
        string|ModelVendor $vendor,
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
        string|ModelVendor $vendor,
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
        string|ModelVendor $vendor,
        ?string $indexId,
        ?string $indexFileId,
    ): void {
        $this->getProvider($vendor)->deleteFile(DomainException::validateId($indexId, 'index'), DomainException::validateId($indexFileId, 'index file'));
    }
}
