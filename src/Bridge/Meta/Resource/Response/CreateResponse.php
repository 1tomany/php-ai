<?php

namespace OneToMany\AI\Bridge\Meta\Resource\Response;

final readonly class CreateResponse implements \JsonSerializable
{
    /**
     * @param non-empty-string $model
     * @param non-empty-list<EasyInputMessage> $input
     * @param ?non-empty-string $instructions
     * @param ?array{
     *   format: array{
     *     type: 'json_schema',
     *     name: non-empty-string,
     *     strict: bool,
     *     schema: array<string, mixed>,
     *   },
     * } $text
     */
    public function __construct(
        public string $model,
        public array $input,
        public ?string $instructions = null,
        public ?array $text = null,
    ) {
    }

    /**
     * @see \JsonSerializable
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        $payload = [
            'model' => $this->model,
            'input' => $this->input,
        ];

        if (null !== $this->instructions) {
            $payload['instructions'] = $this->instructions;
        }

        if (null !== $this->text) {
            $payload['text'] = $this->text;
        }

        return $payload;
    }
}
