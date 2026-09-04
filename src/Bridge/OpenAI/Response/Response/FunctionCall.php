<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class FunctionCall implements OutputInterface
{
    /**
     * @param non-empty-string $id
     * @param 'function_call' $type
     */
    public function __construct(
        public string $id,
        public string $type,
    ) {
    }
}
