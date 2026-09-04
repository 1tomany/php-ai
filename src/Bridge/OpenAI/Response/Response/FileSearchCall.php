<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class FileSearchCall implements OutputInterface
{
    /**
     * @param non-empty-string $id
     * @param 'file_search_call' $type
     * @param 'in_progress'|'searching'|'completed'|'incomplete'|'failed' $status
     * @param list<non-empty-string> $queries
     * @param ?list<FileSearchCallResult> $results
     */
    public function __construct(
        public string $id,
        public string $type,
        public string $status,
        public array $queries = [],
        public ?array $results = null,
    ) {
    }
}
