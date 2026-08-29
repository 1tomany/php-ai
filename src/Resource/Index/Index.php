<?php

namespace OneToMany\AI\Resource\Index;

final readonly class Index
{
    /**
     * @param non-empty-string $id
     * @param ?non-empty-string $name
     * @param ?non-empty-string $model
     */
    public function __construct(
        public string $id,
        public ?string $name = null,
        public ?string $model = null,
        public Usage $usage = new Usage(),
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return ?non-empty-string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ?non-empty-string
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    public function getUsage(): Usage
    {
        return $this->usage;
    }
}
