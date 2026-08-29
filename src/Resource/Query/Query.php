<?php

namespace OneToMany\AI\Resource\Query;

use OneToMany\AI\Model;

final readonly class Query
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public Model $model,
        public array $payload,
    ) {
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
