<?php

namespace OneToMany\AI\Contract\Response\Prompt;

interface SentPromptResponseInterface
{
    /**
     * @var non-empty-lowercase-string
     */
    public string $model { get; }

    /**
     * @var non-empty-string
     */
    public string $uri { get; }

    /**
     * @var ?non-empty-string
     */
    public ?string $text { get; }

    /**
     * @var array<string, mixed>
     */
    public array $response { get; }

    /**
     * @var non-negative-int|float
     */
    public int|float $runtime { get; }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array;
}
