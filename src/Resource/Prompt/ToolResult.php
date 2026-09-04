<?php

namespace OneToMany\AI\Resource\Prompt;

use OneToMany\AI\Resource\Prompt\Enum\ToolType;

readonly class ToolResult
{
    /**
     * @param non-empty-string $id
     */
    public function __construct(
        public string $id,
        public ToolType $type,
        public bool $completed = true,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): ToolType
    {
        return $this->type;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }
}
