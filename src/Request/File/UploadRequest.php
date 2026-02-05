<?php

namespace OneToMany\AI\Request\File;

use OneToMany\AI\Exception\RuntimeException;

use function basename;
use function fclose;
use function filesize;
use function fopen;
use function is_resource;
use function sprintf;
use function strtolower;
use function trim;

class UploadRequest
{
    /**
     * @var non-empty-lowercase-string
     */
    private string $model;

    /**
     * @var ?non-empty-string
     */
    private ?string $path = null;

    /**
     * @var ?non-empty-string
     */
    private ?string $name = null;

    /**
     * @var ?non-negative-int
     */
    private ?int $size = null;

    /**
     * @var non-empty-lowercase-string
     */
    private string $format = 'application/octet-stream';

    /**
     * @var ?non-empty-lowercase-string
     */
    private ?string $purpose = null;

    /**
     * @var ?resource
     */
    private mixed $fileHandle = null;

    public function __construct(string $model = 'mock')
    {
        $this->forModel($model);
    }

    public function __destruct()
    {
        $this->closeFileHandle();
    }

    public function forModel(string $model): static
    {
        $this->model = strtolower(trim($model)) ?: $this->model;

        return $this;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    public function atPath(?string $path): static
    {
        $this->path = trim($path ?? '') ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    public function withName(?string $name): static
    {
        $this->name = trim($name ?? '') ?: (basename($this->path ?? '') ?: null);

        return $this;
    }

    /**
     * @return ?non-empty-string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return non-negative-int
     *
     * @throws RuntimeException the filesize could not be calculated
     */
    public function getSize(): int
    {
        if (null === $this->size) {
            $this->size = @filesize((string) $this->path) ?: throw new RuntimeException('Calculating the size of the file failed.');
        }

        return $this->size;
    }

    public function withFormat(?string $format): static
    {
        $this->format = strtolower(trim($format ?? '')) ?: $this->format;

        return $this;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    public function withPurpose(?string $purpose): static
    {
        $this->purpose = strtolower(trim($purpose ?? '')) ?: null;

        return $this;
    }

    /**
     * @return ?non-empty-lowercase-string
     */
    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    /**
     * @return resource
     *
     * @throws RuntimeException opening the file failed
     */
    public function openFileHandle(): mixed
    {
        if (null === $this->fileHandle) {
            $this->fileHandle = @fopen((string) $this->path, 'r') ?: throw new RuntimeException(sprintf('Opening the file "%s" failed.', $this->path));
        }

        return $this->fileHandle;
    }

    public function closeFileHandle(): void
    {
        if (is_resource($this->fileHandle)) {
            @fclose($this->fileHandle);
        }

        $this->fileHandle = null;
    }
}
