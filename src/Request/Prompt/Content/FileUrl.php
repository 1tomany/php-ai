<?php

namespace OneToMany\AI\Request\Prompt\Content;

use OneToMany\AI\Contract\Input\Request\Content\ContentInterface;
use OneToMany\AI\Contract\Input\Request\Content\Enum\Role;
use OneToMany\AI\Exception\InvalidArgumentException;

use function trim;

final readonly class FileUrl implements ContentInterface
{
    /**
     * @param non-empty-string $url
     */
    public function __construct(public string $url)
    {
    }

    public static function create(?string $url): self
    {
        if (empty($url = trim($url ?? ''))) {
            throw new InvalidArgumentException('The URL cannot be empty.');
        }

        return new self($url);
    }

    /**
     * @see OneToMany\AI\Contract\Input\Request\Content\ContentInterface
     */
    public function getRole(): Role
    {
        return Role::User;
    }
}
