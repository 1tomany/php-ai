<?php

namespace OneToMany\AI\Resource\Query;

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
    private ?Schema $schema = null;

    /**
     * @var ?array<string, mixed>
     */
    private ?array $options = null;

    private function __construct(
        string|Model $model,
    ) {
        $this->model = Model::create($model);
    }

    public static function create(
        string|Model $model,
        string|InputFile|InputText ...$inputs,
    ): static {
        $prompt = new static($model);

        foreach ($inputs as $input) {
            $prompt = $prompt->addInput($input);
        }

        return $prompt;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function addText(string|InputText $text): static
    {
        return $this->addInput($text);
    }

    public function addFile(InputFile $file): static
    {
        return $this->addInput($file);
    }

    /**
     * @return list<InputText|InputFile>
     */
    public function getInputs(): array
    {
        return $this->inputs;
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

    public function getInstructions(): ?InputText
    {
        return $this->instructions;
    }

    /**
     * @param array<string, mixed> $schema
     */
    public function withSchema(
        array $schema,
        ?string $name = null,
        bool $strict = true,
    ): self {
        return $this->addSchema(new Schema($schema, $name, $strict));
    }

    public function withSchemaFile(string $file, ?string $name = null): static
    {
        return $this->addSchema(Schema::fromFile($file, $name));
    }

    public function getSchema(): ?Schema
    {
        return $this->schema;
    }

    /**
     * @param ?array<string, mixed> $options
     */
    public function withOptions(?array $options): static
    {
        $prompt = clone $this;
        $prompt->options = $options;

        return $prompt;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
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

    private function addSchema(Schema $schema): static
    {
        $prompt = clone $this;
        $prompt->schema = $schema;

        return $prompt;
    }
}
