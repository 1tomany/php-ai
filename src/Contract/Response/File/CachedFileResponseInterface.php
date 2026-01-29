<?php

namespace OneToMany\AI\Contract\Response\File;

interface CachedFileResponseInterface
{
    /**
     * @var non-empty-string
     */
    public string $uri { get; }

    /**
     * @var ?non-empty-string
     */
    public ?string $name { get; }

    /**
     * @var ?non-empty-string
     */
    public ?string $purpose { get; }

    public ?\DateTimeImmutable $expiresAt { get; }
}
