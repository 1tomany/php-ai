<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response;

use OneToMany\AI\Client\OpenAi\Type\Error\ErrorType;
use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Status;
use OneToMany\AI\Client\OpenAi\Type\Response\Output\OutputType;
use OneToMany\AI\Exception\RuntimeException;

final readonly class ResponseType
{
    /**
     * @param non-empty-string $id
     * @param 'response' $object
     * @param non-negative-int $created_at
     * @param ?non-negative-int $completed_at
     * @param non-empty-lowercase-string $model
     * @param ?non-empty-list<OutputType> $output
     */
    public function __construct(
        public string $id,
        public string $object,
        public int $created_at,
        public Status $status,
        public ?int $completed_at,
        public ?ErrorType $error,
        public string $model,
        public ?array $output,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getOutput(): string
    {
        throw new RuntimeException('Not implemented!');
    }
}
