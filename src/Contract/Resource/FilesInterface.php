<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\ModelVendor;
use OneToMany\AI\Resource\File\LocalFile;
use OneToMany\AI\Resource\File\RemoteFile;

interface FilesInterface
{
    public function upload(string|ModelVendor $vendor, string|LocalFile $file): RemoteFile;

    /**
     * @throws DomainException when the file ID is empty
     */
    public function delete(string|ModelVendor $vendor, ?string $fileId): void;
}
