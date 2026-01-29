<?php

namespace OneToMany\AI\Request\Prompt\Content;

use OneToMany\AI\Contract\Request\Prompt\Content\ContentInterface;
use OneToMany\AI\Contract\Request\Prompt\Content\Enum\Role;
use OneToMany\AI\Exception\InvalidArgumentException;

use function trim;

final readonly class InputText implements ContentInterface
{
    /**
     * @param non-empty-string $text
     */
    public function __construct(
        public string $text,
        public Role $role = Role::User,
    ) {
    }

    public static function create(?string $text, Role $role = Role::User): self
    {
        if (empty($text = trim($text ?? ''))) {
            throw new InvalidArgumentException('The input text cannot be empty.');
        }

        return new self($text, $role);
    }

    public static function user(?string $text): self
    {
        return self::create($text, Role::User);
    }

    public static function system(?string $text): self
    {
        return self::create($text, Role::System);
    }
}
