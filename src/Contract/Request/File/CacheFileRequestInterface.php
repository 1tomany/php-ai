<?php

namespace OneToMany\AI\Contract\Request\File;

interface CacheFileRequestInterface
{
    /**
     * @var non-empty-string
     */
    public string $vendor { get; }

    /**
     * @var non-empty-string
     */
    public string $path { get; }

    public string $name { get; }

    /**
     * @var non-negative-int
     */
    public int $size { get; }

    /**
     * @var non-empty-lowercase-string
     */
    public string $format { get; }

    /**
     * @var ?non-empty-lowercase-string
     */
    public ?string $purpose { get; }

    /**
     * @return resource
     */
    public function open(): mixed;
}
