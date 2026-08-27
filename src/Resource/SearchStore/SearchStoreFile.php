<?php

namespace OneToMany\AI\Resource\SearchStore;

final readonly class SearchStoreFile
{
    /**
     * @param non-empty-string $id
     */
    public function __construct(
        public string $id,
        public bool $isActive = true,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
