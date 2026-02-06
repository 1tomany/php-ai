<?php

namespace OneToMany\AI\Client\OpenAi\Type\File;

use OneToMany\AI\Client\OpenAi\Type\File\Enum\Purpose;

final readonly class File
{
    /**
     * @param 'file' $object
     * @param non-empty-string $id
     * @param non-empty-string $filename
     * @param non-negative-int $bytes
     * @param non-negative-int $created_at
     * @param ?non-negative-int $expires_at
     */
    public function __construct(
        public string $object,
        public string $id,
        public Purpose $purpose,
        public string $filename,
        public int $bytes,
        public int $created_at,
        public ?int $expires_at = null,
    ) {
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return null !== $this->expires_at ? \DateTimeImmutable::createFromTimestamp($this->expires_at) : null;
    }
}
