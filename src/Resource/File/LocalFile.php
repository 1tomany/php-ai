<?php

namespace OneToMany\AI\Resource\File;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Exception\RuntimeException;

use function basename;
use function filesize;
use function is_file;
use function is_readable;
use function mime_content_type;
use function sprintf;
use function strtolower;
use function trim;

final readonly class LocalFile
{
    /**
     * @var non-empty-string
     */
    public string $path;

    /**
     * @var non-empty-string
     */
    public string $name;

    /**
     * @var non-empty-lowercase-string
     */
    public string $type;

    /**
     * @var non-negative-int
     */
    public int $size;

    /**
     * @throws DomainException when the file path is empty
     * @throws DomainException when the file is not readable
     * @throws DomainException when the file name is empty
     * @throws DomainException when the type is empty
     * @throws RuntimeException when calculating the file size fails
     */
    public function __construct(
        string $path,
        ?string $type = null,
        ?string $name = null,
    ) {
        if ('' === $path = trim($path)) {
            throw new DomainException('The file path cannot be empty.');
        }

        if (!is_file($path) || !is_readable($path)) {
            throw new DomainException(sprintf('The file "%s" is not readable.', $path));
        }

        $this->path = $path;

        if ('' === $name = trim($name ?? basename($path))) {
            throw new DomainException('The file name cannot be empty.');
        }

        $this->name = $name;

        if ('' === $type = trim((string) $type)) {
            $type = @mime_content_type(filename: $path);
        }

        if (false === $type || '' === $type) {
            throw new DomainException('The MIME type cannot be empty.');
        }

        $this->type = strtolower($type);

        if (false === $size = @filesize($path)) {
            throw new RuntimeException(sprintf('Calculating the size of the file "%s" failed.', $path));
        }

        $this->size = $size;
    }

    /**
     * @return non-empty-string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return non-negative-int
     */
    public function getSize(): int
    {
        return $this->size;
    }
}
