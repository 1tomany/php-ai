<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

final readonly class ResponseOutputMessage
{
    /**
     * @param non-empty-string $id
     * @param 'message' $type
     * @param 'in_progress'|'completed'|'incomplete' $status
     * @param list<ResponseOutputText|ResponseOutputRefusal> $content
     * @param 'commentary'|'final_answer'|null $phase
     * @param 'assistant' $role
     */
    public function __construct(
        public string $id,
        public string $type,
        public string $status,
        public array $content,
        public ?string $phase,
        public string $role,
    ) {
    }
}
