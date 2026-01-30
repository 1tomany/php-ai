<?php

namespace OneToMany\AI\Contract\Input\File;

use OneToMany\AI\Exception\RuntimeException;

interface CacheFileInputInterface
{
    /**
     * @return non-empty-lowercase-string
     */
    public function getVendor(): string;

    /**
     * @return non-empty-string
     */
    public function getPath(): string;

    public function getName(): string;

    /**
     * @return non-negative-int
     */
    public function getSize(): int;

    /**
     * @return non-empty-lowercase-string
     */
    public function getFormat(): string;

    /**
     * @return ?non-empty-string
     */
    public function getPurpose(): ?string;

    /**
     * @return resource
     *
     * @throws RuntimeException opening the file fails
     */
    public function open(): mixed;
}
