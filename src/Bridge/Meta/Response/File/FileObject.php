<?php

namespace OneToMany\AI\Bridge\Meta\Response\File;

use OneToMany\AI\Resource\File\RemoteFile;

final readonly class FileObject
{
    /**
     * @param non-empty-string $id
     * @param 'file' $object
     * @param non-negative-int $bytes
     * @param positive-int|float $created_at
     * @param positive-int|float|null $expires_at
     * @param non-empty-string $filename
     * @param 'batch'|'evals'|'fine-tune'|'user_data' $purpose
     * @param 'error'|'processed'|'uploaded' $status
     */
    public function __construct(
        public string $id,
        public string $object,
        public int $bytes,
        public int|float $created_at,
        public int|float|null $expires_at,
        public string $filename,
        public string $purpose,
        public string $status,
        public ?string $status_details = null,
    ) {
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        if (null === $this->expires_at) {
            return null;
        }

        try {
            return \DateTimeImmutable::createFromTimestamp((int) $this->expires_at);
        } catch (\DateError) {
        }

        return null;
    }

    public function toResource(): RemoteFile
    {
        return new RemoteFile($this->id, $this->getExpiresAt(), $this->id, $this->purpose);
    }
}
