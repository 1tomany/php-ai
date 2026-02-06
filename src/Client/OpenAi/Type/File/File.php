<?php

namespace OneToMany\AI\Client\OpenAi\Type\File;

use OneToMany\AI\Client\OpenAi\Type\File\Enum\Purpose;

final readonly class File
{
    /**
     * @param non-empty-string $id
     * @param 'file' $object
     * @param non-negative-int $bytes
     * @param non-negative-int $created_at
     * @param ?non-negative-int $expires_at
     * @param non-empty-string $filename
     */
    public function __construct(
        public string $id,
        public string $object,
        public int $bytes,
        public int $created_at,
        public ?int $expires_at,
        public string $filename,
        public Purpose $purpose,
    )
    {
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return null !== $this->expires_at ? \DateTimeImmutable::createFromTimestamp($this->expires_at) : null;
    }
}
