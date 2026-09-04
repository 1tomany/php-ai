<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class WebSearchCall implements OutputInterface
{
    /**
     * @param non-empty-string $id
     * @param 'web_search_call' $type
     * @param 'in_progress'|'searching'|'completed'|'failed'|'incomplete' $status
     */
    public function __construct(
        public string $id,
        public string $type,
        public string $status,
    ) {
    }
}
