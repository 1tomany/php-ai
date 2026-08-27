<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

use OneToMany\AI\Bridge\OpenAI\Response\VectorStore\Enum\ChunkingStrategyType;

final readonly class ChunkingStrategy
{
    public function __construct(
        public ChunkingStrategyType $type,
        public ?StaticFileChunkingStrategy $static = null,
    ) {
    }
}
