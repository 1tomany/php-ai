<?php

namespace OneToMany\AI\Request\Prompt\Content;

use OneToMany\AI\Contract\Request\Prompt\Content\ContentInterface;
use OneToMany\AI\Contract\Request\Prompt\Content\Enum\Role;

use function is_string;
use function strtolower;
use function trim;

final readonly class JsonSchema implements ContentInterface
{
    public Role $role;

    /**
     * @param non-empty-lowercase-string $name
     * @param array<string, mixed> $schema
     * @param non-empty-lowercase-string $format
     */
    public function __construct(
        public string $name,
        public array $schema,
        public string $format = 'application/json',
    ) {
        $this->role = Role::User;
    }

    /**
     * @param array<string, mixed> $schema
     */
    public static function create(?string $name, array $schema): self
    {
        // Attempt to resolve the name
        $name = trim($name ?? '') ?: null;

        if (isset($schema['title'])) {
            $name ??= $schema['title'];
        }

        if (!is_string($name) || !$name) {
            $name = 'json_schema';
        }

        return new self(strtolower($name), $schema);
    }
}
