<?php

namespace OneToMany\AI\Request\Query;

use function strtolower;
use function trim;

class ExecuteRequest
{
    /**
     * @var non-empty-lowercase-string
     */
    private string $model;

    /**
     * @var non-empty-string
     */
    private string $url;

    /**
     * @var array<string, mixed>
     */
    private array $request = [];

    public function __construct(
        string $model = 'mock',
        string $url = 'mock',
    ) {
        $this->forModel($model)->withUrl($url);
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

    public function withUrl(?string $url): static
    {
        $this->url = trim($url ?? '') ?: $this->url;

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param array<string, mixed> $request
     */
    public function withRequest(array $request): static
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
