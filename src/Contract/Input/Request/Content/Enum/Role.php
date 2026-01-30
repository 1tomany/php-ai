<?php

namespace OneToMany\AI\Contract\Input\Request\Content\Enum;

enum Role: string
{
    case User = 'user';
    case System = 'system';

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
     * @phpstan-assert-if-true self::User $this
     * @phpstan-assert-if-true 'user' $this->getValue()
     */
    public function isUser(): bool
    {
        return self::User === $this;
    }

    /**
     * @phpstan-assert-if-true self::System $this
     * @phpstan-assert-if-true 'system' $this->getValue()
     */
    public function isSystem(): bool
    {
        return self::System === $this;
    }
}
