<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\FileProviderInterface;
use OneToMany\AI\Contract\Resource\FilesInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\File\LocalFile;
use OneToMany\AI\Resource\File\RemoteFile;
use OneToMany\AI\ModelVendor;

/**
 * @extends Resources<FileProviderInterface>
 */
final readonly class Files extends Resources implements FilesInterface
{
    /**
     * @see OneToMany\AI\Contract\Resource\FilesInterface
     */
    #[\Override]
    public function upload(
        string|ModelVendor $vendor,
        string|LocalFile $file,
    ): RemoteFile {
        if (!$file instanceof LocalFile) {
            $file = new LocalFile($file);
        }

        return $this->getProvider($vendor)->upload($file);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\FilesInterface
     */
    #[\Override]
    public function delete(
        string|ModelVendor $vendor,
        ?string $fileId,
    ): void {
        $this->getProvider($vendor)->delete(DomainException::validateId($fileId, 'file'));
    }
}
