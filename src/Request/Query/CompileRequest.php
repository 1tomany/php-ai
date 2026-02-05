<?php

namespace App\File\Vendor\AI\Request\Query;

use App\File\Vendor\AI\Contract\Request\Query\Component\ComponentInterface;
use App\File\Vendor\AI\Contract\Request\Query\Component\Enum\Role;
use App\File\Vendor\AI\Request\Query\Component\FileUriComponent;
use App\File\Vendor\AI\Request\Query\Component\SchemaComponent;
use App\File\Vendor\AI\Request\Query\Component\TextComponent;

use function strtolower;
use function trim;

class CompileRequest
{
    /**
     * @var non-empty-lowercase-string
     */
    private string $model = 'mock';

    /**
     * @var list<ComponentInterface>
     */
    private array $components = [];

    public function __construct(string $model = 'mock')
    {
        $this->forModel($model);
    }

    public function forModel(string $model): static
    {
        $this->model = strtolower(trim($model)) ?: $this->model;

        return $this;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param ?non-empty-string $fileUri
     */
    public function withFileUri(?string $fileUri): static
    {
        if (!empty($fileUri = trim($fileUri ?? ''))) {
            $this->addComponent(new FileUriComponent($fileUri));
        }

        return $this;
    }

    /**
     * @param array<string, mixed> $schema
     */
    public function usingSchema(array $schema): static
    {
        return $this->addComponent(new SchemaComponent(null, $schema));
    }

    public function withText(?string $text, Role $role = Role::User): static
    {
        if (!empty($text = trim($text ?? ''))) {
            $this->addComponent(new TextComponent($text, $role));
        }

        return $this;
    }

    public function withSystemText(?string $text): static
    {
        return $this->withText($text, Role::System);
    }

    public function addComponent(ComponentInterface $component): static
    {
        $this->components[] = $component;

        return $this;
    }

    /**
     * @return list<ComponentInterface>
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @phpstan-assert-if-true non-empty-list<ComponentInterface> $this->getComponents()
     */
    public function hasComponents(): bool
    {
        return [] !== $this->getComponents();
    }
}
