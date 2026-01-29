<?php

namespace OneToMany\AI\Request\File;

use OneToMany\AI\Contract\Request\File\CacheFileRequestInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Exception\RuntimeException;

use function fopen;
use function is_file;
use function is_readable;
use function sprintf;

final readonly class CacheFileRequest implements CacheFileRequestInterface
{
    /**
     * @param non-empty-lowercase-string $vendor
     * @param non-empty-string $path
     * @param non-negative-int $size
     * @param non-empty-lowercase-string $format
     * @param ?non-empty-lowercase-string $purpose
     *
     * @throws InvalidArgumentException the file does not exist or is not readable
     */
    public function __construct(
        private string $vendor,
        private string $path,
        private string $name,
        private int $size,
        private string $format,
        private ?string $purpose = null,
    ) {
        if (!is_file($this->path) || !is_readable($this->path)) {
            throw new InvalidArgumentException(sprintf('The file "%s" does not exist or is not readable.', $path));
        }
    }

    /**
     * @see OneToMany\AI\Contract\Request\File\CacheFileRequestInterface
     */
    public function getVendor(): string
    {
        return $this->vendor;
    }

    /**
     * @see OneToMany\AI\Contract\Request\File\CacheFileRequestInterface
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @see OneToMany\AI\Contract\Request\File\CacheFileRequestInterface
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @see OneToMany\AI\Contract\Request\File\CacheFileRequestInterface
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @see OneToMany\AI\Contract\Request\File\CacheFileRequestInterface
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @see OneToMany\AI\Contract\Request\File\CacheFileRequestInterface
     */
    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    /**
     * @see OneToMany\AI\Contract\Request\File\CacheFileRequestInterface
     */
    public function open(): mixed
    {
        return @fopen($this->path, 'r') ?: throw new RuntimeException(sprintf('Opening the file "%s" failed.', $this->name));
    }
}
