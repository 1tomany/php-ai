<?php

namespace OneToMany\AI\Request\Prompt\Content;

use OneToMany\AI\Contract\Input\Request\Content\ContentInterface;
use OneToMany\AI\Contract\Input\Request\Content\Enum\Role;
use OneToMany\AI\Exception\InvalidArgumentException;

use function strtolower;
use function trim;

final readonly class CachedFile implements ContentInterface
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

    public static function create(?string $uri, ?string $format): self
    {
        if (empty($uri = trim($uri ?? ''))) {
            throw new InvalidArgumentException('The URI cannot be empty.');
        }

        return new self($uri, strtolower(trim($format ?? '') ?: 'application/octet-stream'));
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

    /**
     * @see OneToMany\AI\Contract\Input\Request\Content\ContentInterface
     */
    public function getRole(): Role
    {
        return Role::User;
    }
}
