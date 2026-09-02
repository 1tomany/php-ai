<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\File;

use OneToMany\AI\Resource\File\RemoteFile;

final readonly class FileObject
{
    /**
     * @param non-empty-string $id
     * @param 'file' $object
     * @param positive-int $created_at
     * @param ?positive-int $expires_at
     * @param non-empty-string $purpose
     */
    public function __construct(
        public string $id,
        public string $object,
        public int $created_at,
        public ?int $expires_at,
        public string $filename,
        public string $purpose,
    ) {
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        if (null === $this->expires_at) {
            return $this->expires_at;
        }

        try {
            return \DateTimeImmutable::createFromTimestamp(...[
                'timestamp' => $this->expires_at,
            ]);
        } catch (\DateError) {
        }

        return null;
    }

    public function toResource(): RemoteFile
    {
        return new RemoteFile($this->id, $this->getExpiresAt(), $this->id, $this->purpose);
    }
}
