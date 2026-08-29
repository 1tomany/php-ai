<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\File\LocalFile;
use OneToMany\AI\Resource\File\RemoteFile;
use OneToMany\AI\Vendor;

interface FilesInterface
{
    public function upload(string|Vendor $vendor, string|LocalFile $file): RemoteFile;

    /**
     * @throws DomainException when the file ID is empty
     */
    public function delete(string|Vendor $vendor, ?string $fileId): void;
}
