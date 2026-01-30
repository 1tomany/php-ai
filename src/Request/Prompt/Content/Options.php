<?php

namespace OneToMany\AI\Request\Prompt\Content;

use OneToMany\AI\Contract\Input\Request\Content\ContentInterface;
use OneToMany\AI\Contract\Input\Request\Content\Enum\Role;

final readonly class Options implements ContentInterface
{
    /**
     * @param array<non-empty-string, mixed> $options
     */
    public function __construct(public array $options)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Input\Request\Content\ContentInterface
     */
    public function getRole(): Role
    {
        return Role::User;
    }
}
