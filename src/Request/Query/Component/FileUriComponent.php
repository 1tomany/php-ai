<?php

namespace OneToMany\AI\Request\Query\Component;

use OneToMany\AI\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\AI\Contract\Request\Query\Component\Enum\Role;

final readonly class FileUriComponent implements ComponentInterface
{
    /**
     * @param non-empty-string $uri
     */
    public function __construct(
        private string $uri,
        private Role $role = Role::User,
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
     * @see OneToMany\AI\Contract\Request\Query\Component\ComponentInterface
     */
    public function getRole(): Role
    {
        return $this->role;
    }
}
