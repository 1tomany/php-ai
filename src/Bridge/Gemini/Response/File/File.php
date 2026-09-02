<?php

namespace OneToMany\AI\Bridge\Gemini\Response\File;

use OneToMany\AI\Resource\File\RemoteFile;

final readonly class File
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $displayName
     * @param non-empty-string $mimeType
     * @param non-negative-int|numeric-string $sizeBytes
     * @param non-empty-string $createTime
     * @param non-empty-string $sha256Hash
     * @param non-empty-string $sha256Hash
     * @param non-empty-string $uri
     * @param non-empty-string $state
     * @param non-empty-string $source
     */
    public function __construct(
        public string $name,
        public string $displayName,
        public string $mimeType,
        public int|string $sizeBytes,
        public string $createTime,
        public string $updateTime,
        public string $expirationTime,
        public string $sha256Hash,
        public string $uri,
        public string $state,
        public string $source,
    ) {
    }

    public function toResource(): RemoteFile
    {
        return new RemoteFile($this->name, \date_create_immutable($this->expirationTime) ?: null, $this->uri);
    }
}
