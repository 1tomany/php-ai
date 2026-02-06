<?php

namespace OneToMany\AI\Request\Query\Component;

use OneToMany\AI\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\AI\Contract\Request\Query\Component\Enum\Role;

final readonly class FileUriComponent implements ComponentInterface
{
    /**
     * @param non-empty-string $uri
     * @param non-empty-lowercase-string $format
     */
    public function __construct(
        private string $uri,
        private string $format,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    public function isImage(): bool
    {
        return \str_starts_with($this->getFormat(), 'image/');
    }

    /**
     * @see OneToMany\AI\Contract\Request\Query\Component\ComponentInterface
     */
    public function getRole(): Role
    {
        return Role::User;
    }
}
