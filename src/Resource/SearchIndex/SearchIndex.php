<?php

namespace OneToMany\AI\Resource\SearchIndex;

final readonly class SearchIndex
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $name
     * @param ?non-empty-string $description
     * @param ?non-empty-string $status
     */
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description = null,
        public ?string $status = null,
        public Statistics $statistics = new Statistics(),
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
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ?non-empty-string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return ?non-empty-string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getStatistics(): Statistics
    {
        return $this->statistics;
    }
}
