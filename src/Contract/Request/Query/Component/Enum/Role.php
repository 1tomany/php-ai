<?php

namespace OneToMany\AI\Contract\Request\Query\Component\Enum;

enum Role: string
{
    case System = 'system';
    case User = 'user';

    /**
     * @return 'System'|'User'
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return 'system'|'user'
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @phpstan-assert-if-true self::System $this
     */
    public function isSystem(): bool
    {
        return self::System === $this;
    }

    /**
     * @phpstan-assert-if-true self::User $this
     */
    public function isUser(): bool
    {
        return self::User === $this;
    }
}
