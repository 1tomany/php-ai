<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\VectorStore;

final readonly class StaticFileChunkingStrategy
{
    /**
     * @param positive-int $chunk_overlap_tokens
     * @param int<100, 4096> $max_chunk_size_tokens
     */
    public function __construct(
        public int $chunk_overlap_tokens = 400,
        public int $max_chunk_size_tokens = 800,
    ) {
    }
}
