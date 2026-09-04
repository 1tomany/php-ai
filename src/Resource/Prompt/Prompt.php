<?php

namespace OneToMany\AI\Resource\Prompt;

use OneToMany\AI\Model;

final class Prompt
{
    private readonly Model $model;
    private ?InputText $instructions = null;
    private ?Schema $schema = null;

    /**
     * @var list<InputFile|InputText>
     */
    private array $inputs = [];

    /**
     * @var list<Tool>
     */
    private array $tools = [];

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
        string|InputFile|InputText|Tool ...$inputs,
    ): static {
        $prompt = new static($model);

        foreach ($inputs as $input) {
            if ($input instanceof Tool) {
                $prompt = $prompt->addTool($input);
            } else {
                $prompt = $prompt->addInput($input);
            }
        }

        return $prompt;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return non-empty-string
     */
    public function getModelId(): string
    {
        return $this->getModel()->getId();
    }

    public function getInstructions(): ?InputText
    {
        return $this->instructions;
    }

    public function getSchema(): ?Schema
    {
        return $this->schema;
    }

    /**
     * @return list<InputText|InputFile>
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
    }

    /**
     * @return list<Tool>
     */
    public function getTools(): array
    {
        return $this->tools;
    }

    public function addFile(InputFile $file): static
    {
        return $this->addInput($file);
    }

    public function addText(string|InputText $text): static
    {
        return $this->addInput($text);
    }

    public function addTool(Tool $tool): static
    {
        $prompt = clone $this;
        $prompt->tools[] = $tool;

        return $prompt;
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

    public function withInstructionsFile(string $file): static
    {
        return $this->withInstructions(InputText::fromFile($file));
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

    /**
     * @param ?array<string, mixed> $options
     */
    public function withOptions(?array $options): static
    {
        $prompt = clone $this;
        $prompt->options = $options;

        return $prompt;
    }

    public function isEmpty(): bool
    {
        return [] === $this->inputs;
    }

    private function addInput(string|InputFile|InputText $input): static
    {
        if (
            !$input instanceof InputFile
            && !$input instanceof InputText
        ) {
            $input = new InputText($input);
        }

        $prompt = clone $this;
        $prompt->inputs[] = $input;

        return $prompt;
    }

    private function addSchema(Schema $schema): static
    {
        $prompt = clone $this;
        $prompt->schema = $schema;

        return $prompt;
    }
}
