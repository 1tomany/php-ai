<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response;

use OneToMany\AI\Client\OpenAi\Type\Error\ErrorType;
use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Status;
use OneToMany\AI\Client\OpenAi\Type\Response\Output\OutputType;
use OneToMany\AI\Exception\RuntimeException;

use function array_map;
use function implode;
use function trim;

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
        $output = null;

        if (null !== $this->output) {
            $output = trim(implode('', array_map(fn ($o) => $o->getOutput(), $this->output)));
        }

        return $output ?: throw new RuntimeException('The query failed to generate any output.');
    }
}
