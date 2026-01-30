<?php

namespace OneToMany\AI\Request\Prompt\Content;

use OneToMany\AI\Contract\Input\Request\Content\ContentInterface;
use OneToMany\AI\Contract\Input\Request\Content\Enum\Role;
use OneToMany\AI\Exception\InvalidArgumentException;

use function trim;

final readonly class InputText implements ContentInterface
{
    /**
     * @param non-empty-string $text
     */
    public function __construct(
        private string $text,
        private Role $role = Role::User,
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

    /**
     * @return non-empty-string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @see OneToMany\AI\Contract\Input\Request\Content\ContentInterface
     */
    public function getRole(): Role
    {
        return $this->role;
    }
}
