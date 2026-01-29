<?php

namespace OneToMany\AI\Response\Prompt;

use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;

final readonly class DispatchedPromptResponse implements DispatchedPromptResponseInterface
{
    /**
     * @param non-empty-lowercase-string $vendor
     * @param non-empty-lowercase-string $model
     * @param non-empty-string $uri
     * @param ?non-empty-string $output
     * @param array<string, mixed> $response
     * @param non-negative-int|float $runtime
     */
    public function __construct(
        public string $vendor,
        public string $model,
        public string $uri,
        public ?string $output,
        public array $response,
        public int|float $runtime = 0,
    ) {
    }

    /**
     * @see OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface
     */
    public function __invoke(): array
    {
        return $this->response;
    }

    /**
     * @see OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface
     */
    public function getVendor(): string
    {
        return $this->vendor;
    }

    /**
     * @see OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @see OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @see OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface
     */
    public function getOutput(): ?string
    {
        return $this->output;
    }

    /**
     * @see OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @see OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface
     */
    public function getRuntime(): int|float
    {
        return $this->runtime;
    }
}
