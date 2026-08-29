<?php

namespace OneToMany\AI\Resource\Index;

final readonly class Index
{
    /**
     * @param non-empty-string $id
     * @param ?non-empty-string $name
     */
    public function __construct(
        public string $id,
        public ?string $name = null,
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

    public function getUsage(): Usage
    {
        return $this->usage;
    }
}
