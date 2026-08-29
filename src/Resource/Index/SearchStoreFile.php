<?php

namespace OneToMany\AI\Resource\Index;

use OneToMany\AI\Resource\Index\Enum\FileState;

use function max;

final readonly class SearchStoreFile
{
    /**
     * @param non-empty-string $id
     * @param non-negative-int $bytes
     */
    public function __construct(
        public string $id,
        public FileState $state = FileState::Pending,
        public int $bytes = 0,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getState(): FileState
    {
        return $this->state;
    }

    /**
     * @return non-negative-int
     */
    public function getBytes(): int
    {
        return max(0, $this->bytes);
    }
}
