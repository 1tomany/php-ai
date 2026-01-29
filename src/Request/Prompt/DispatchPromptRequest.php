<?php

namespace OneToMany\AI\Request\Prompt;

use OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface;
use OneToMany\AI\Exception\InvalidArgumentException;

final readonly class DispatchPromptRequest implements DispatchPromptRequestInterface
{
    /**
     * @param non-empty-lowercase-string $vendor
     * @param non-empty-lowercase-string $model
     * @param array<string, mixed> $request
     *
     * @throws InvalidArgumentException the request is empty
     */
    public function __construct(
        private string $vendor,
        private string $model,
        private array $request,
    ) {
        if ([] === $request) {
            throw new InvalidArgumentException('The request cannot be empty.');
        }
    }

    /**
     * @see OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface
     */
    public function getVendor(): string
    {
        return $this->vendor;
    }

    /**
     * @see OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @see OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
