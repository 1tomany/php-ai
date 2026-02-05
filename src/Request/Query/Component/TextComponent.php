<?php

namespace OneToMany\AI\Request\Query\Component;

use OneToMany\AI\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\AI\Contract\Request\Query\Component\Enum\Role;

final readonly class TextComponent implements ComponentInterface
{
    /**
     * @param non-empty-string $text
     */
    public function __construct(
        private string $text,
        private Role $role = Role::User,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @see OneToMany\AI\Contract\Request\Query\Component\ComponentInterface
     */
    public function getRole(): Role
    {
        return $this->role;
    }
}
