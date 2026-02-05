<?php

namespace OneToMany\AI\Response\Query;

final readonly class CompileResponse
{
    /**
     * @param non-empty-lowercase-string $model
     * @param non-empty-string $url
     * @param array<string, mixed> $request
     */
    public function __construct(
        private string $model,
        private string $url,
        private array $request,
    ) {
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return non-empty-string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
