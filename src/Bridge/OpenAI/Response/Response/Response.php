<?php

namespace OneToMany\AI\Bridge\OpenAI\Response\Response;

use OneToMany\AI\Bridge\OpenAI\Response\Response\Enum\ResponseStatus;

final readonly class Response
{
    /**
     * @param non-empty-string $id
     * @param 'response' $object
     * @param positive-int $created_at
     * @param ?positive-int $completed_at
     * @param ?non-negative-int $max_output_tokens
     * @param ?non-negative-int $max_tool_calls
     * @param non-empty-string $model
     * @param list<ResponseOutputItem> $output
     */
    public function __construct(
        public string $id,
        public string $object,
        public int $created_at,
        public ResponseStatus $status,
        public ?int $completed_at,
        public ?ResponseError $error,
        public ?IncompleteDetails $incomplete_details,
        public ?int $max_output_tokens,
        public ?int $max_tool_calls,
        public string $model,
        public array $output = [],
    ) {
    }
}
