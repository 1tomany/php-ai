<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class Compaction implements OutputInterface
{
    /**
     * @param non-empty-string $id
     * @param 'compaction' $type
     */
    public function __construct(
        public string $id,
        public string $type,
    ) {
    }
}
