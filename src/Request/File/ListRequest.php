<?php

namespace OneToMany\AI\Request\File;

use function strtolower;
use function trim;

class ListRequest
{
    /**
     * @var non-empty-lowercase-string
     */
    private string $model;

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
}
