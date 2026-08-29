<?php

namespace OneToMany\AI\Resource\SearchStore;

final readonly class SearchStore
{
    /**
     * @param non-empty-string $id
     * @param non-empty-string $name
     * @param non-negative-int $bytes
     */
    public function __construct(
        public string $id,
        public string $name,
        public int $bytes = 0,
        public Counts $counts = new Counts(),
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
     * @return non-negative-int
     */
    public function getBytes(): int
    {
        return $this->bytes;
    }

    public function getCounts(): Counts
    {
        return $this->counts;
    }
}
