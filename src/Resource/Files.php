<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\FileProviderInterface;
use OneToMany\AI\Contract\Resource\FilesInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Resource\File\LocalFile;
use OneToMany\AI\Resource\File\RemoteFile;
use OneToMany\AI\Vendor;

use function trim;

/**
 * @extends AbstractResource<FileProviderInterface>
 */
final readonly class Files extends AbstractResource implements FilesInterface
{
    /**
     * @see OneToMany\AI\Contract\Resource\FilesInterface
     */
    #[\Override]
    public function upload(string|Vendor $vendor, string|LocalFile $file): RemoteFile
    {
        if (!$file instanceof LocalFile) {
            $file = new LocalFile($file);
        }

        return $this->providers->get(Vendor::create($vendor))->upload($file);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\FilesInterface
     */
    #[\Override]
    public function delete(string|Vendor $vendor, ?string $fileId): void
    {
        if ('' === $fileId = trim((string) $fileId)) {
            throw new InvalidArgumentException('The file ID cannot be empty.');
        }

        $this->providers->get(Vendor::create($vendor))->delete($fileId);
    }
}
