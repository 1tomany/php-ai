<?php

namespace OneToMany\AI\Request\Prompt\Content;

use OneToMany\AI\Contract\Request\Prompt\Content\ContentInterface;
use OneToMany\AI\Contract\Request\Prompt\Content\Enum\Role;
use OneToMany\AI\Exception\InvalidArgumentException;

use function trim;

final readonly class FileUrl implements ContentInterface
{
    public Role $role;

    /**
     * @param non-empty-string $url
     */
    public function __construct(public string $url)
    {
        $this->role = Role::User;
    }

    public static function create(?string $url): self
    {
        if (empty($url = trim($url ?? ''))) {
            throw new InvalidArgumentException('The URL cannot be empty.');
        }

        return new self($url);
    }
}
