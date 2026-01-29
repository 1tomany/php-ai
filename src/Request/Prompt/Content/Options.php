<?php

namespace OneToMany\AI\Request\Prompt\Content;

use OneToMany\AI\Contract\Request\Prompt\Content\ContentInterface;
use OneToMany\AI\Contract\Request\Prompt\Content\Enum\Role;

final readonly class Options implements ContentInterface
{
    public Role $role;

    /**
     * @param array<non-empty-string, mixed> $options
     */
    public function __construct(public array $options)
    {
    }
}
