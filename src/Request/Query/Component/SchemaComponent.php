<?php

namespace OneToMany\AI\Request\Query\Component;

use OneToMany\AI\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\AI\Contract\Request\Query\Component\Enum\Role;

final readonly class SchemaComponent implements ComponentInterface
{
    /**
     * @param ?non-empty-lowercase-string $name
     * @param array<string, mixed> $schema
     */
    public function __construct(
        private ?string $name,
        private array $schema,
        private Role $role = Role::User,
    ) {
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getName(): string
    {
        return $this->name ?? 'json_schema';
    }

    /**
     * @return array<string, mixed>
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getFormat(): string
    {
        return 'application/json';
    }

    /**
     * @see OneToMany\AI\Contract\Request\Query\Component\ComponentInterface
     */
    public function getRole(): Role
    {
        return $this->role;
    }
}
