<?php

namespace OneToMany\AI\Response\File;

use OneToMany\AI\Contract\Response\File\CachedFileResponseInterface;

final readonly class CachedFileResponse implements CachedFileResponseInterface
{
    /**
     * @param non-empty-lowercase-string $vendor
     * @param non-empty-string $uri
     * @param ?non-empty-string $name
     * @param ?non-empty-string $purpose
     */
    public function __construct(
        public string $vendor,
        public string $uri,
        public ?string $name = null,
        public ?string $purpose = null,
        public ?\DateTimeImmutable $expiresAt = null,
    ) {
    }

    /**
     * @see OneToMany\AI\Contract\Response\File\CachedFileResponseInterface
     */
    public function getVendor(): string
    {
        return $this->vendor;
    }

    /**
     * @see OneToMany\AI\Contract\Response\File\CachedFileResponseInterface
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @see OneToMany\AI\Contract\Response\File\CachedFileResponseInterface
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @see OneToMany\AI\Contract\Response\File\CachedFileResponseInterface
     */
    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    /**
     * @see OneToMany\AI\Contract\Response\File\CachedFileResponseInterface
     */
    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }
}
