<?php

namespace OneToMany\AI\Resource\Query;

use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Model;

use function is_string;

final class Prompt
{
    private readonly Model $model;

    /**
     * @var list<InputFile|InputText>
     */
    private array $inputs = [];
    private ?InputText $instructions = null;
    private ?JsonSchema $schema = null;

    private function __construct(
        string|Model $model,
    ) {
        $this->model = Model::create($model);
    }

    public static function create(
        string|Model $model,
        string|InputFile|InputText ...$inputs,
    ): static
    {
        $prompt = new static($model);

        foreach ($inputs as $input) {
            $prompt = $prompt->addInput($input);
        }

        return $prompt;
    }

    public function addText(string|InputText $text): static
    {
        return $this->addInput($text);
    }

    public function addFile(InputFile $file): static
    {
        return $this->addInput($file);
    }

    public function withInstructions(string|InputText $text): static
    {
        if (!$text instanceof InputText) {
            $text = new InputText($text);
        }

        $prompt = clone $this;
        $prompt->instructions = $text;

        return $prompt;
    }

    /**
     * @param array<string, mixed> $schema
     */
    public function withSchema(
        array $schema,
        ?string $name = null,
        bool $strict = true,
    ): self {
        return $this->addSchema(new JsonSchema($schema, $name, $strict));
    }

    public function withSchemaFile(string $file, ?string $name = null): static
    {
        return $this->addSchema(JsonSchema::fromFile($file, $name));
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return list<InputText|InputFile>
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }

    public function getInstructions(): ?InputText
    {
        return $this->instructions;
    }

    public function getSchema(): ?JsonSchema
    {
        return $this->schema;
    }

    public function isEmpty(): bool
    {
        return [] === $this->inputs;
    }

    private function addInput(string|InputFile|InputText $input): static
    {
        $prompt = clone $this;
        $prompt->inputs[] = is_string($input) ? new InputText($input) : $input;

        return $prompt;
    }

    private function addSchema(JsonSchema $schema): static
    {
        $prompt = clone $this;
        $prompt->schema = $schema;

        return $prompt;
    }
}
