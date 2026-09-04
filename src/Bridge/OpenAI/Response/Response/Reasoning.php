<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class Reasoning implements OutputInterface
{
    /**
     * @param non-empty-string $id
     * @param 'reasoning' $type
     */
    public function __construct(
        public string $id,
        public string $type,
    ) {
    }
}
