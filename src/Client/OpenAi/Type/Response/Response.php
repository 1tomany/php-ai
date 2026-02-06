<?php

namespace OneToMany\AI\Client\OpenAi\Type\Response;

use OneToMany\AI\Client\OpenAi\Type\Error\Error;
use OneToMany\AI\Client\OpenAi\Type\Response\Enum\Status;
use OneToMany\AI\Client\OpenAi\Type\Response\Output\Output;
use OneToMany\AI\Exception\RuntimeException;

use function array_map;
use function implode;
use function sprintf;
use function trim;

final readonly class Response
{
    /**
     * @param non-empty-string $id
     * @param 'response' $object
     * @param non-negative-int $created_at
     * @param ?non-negative-int $completed_at
     * @param non-empty-lowercase-string $model
     * @param ?list<Output> $output
     */
    public function __construct(
        public string $id,
        public string $object,
        public int $created_at,
        public Status $status,
        public ?bool $background,
        public ?int $completed_at,
        public ?Error $error,
        public string $model,
        public ?array $output,
        public int|float $temperature = 1.0,
        public Usage $usage = new Usage(),
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getOutput(): string
    {
        if (null !== $this->output && [] !== $this->output) {
            $output = array_map(fn ($o) => $o->getOutput(), $this->output);
        }

        return trim(implode('', $output ?? [])) ?: throw new RuntimeException(sprintf('The model "%s" failed to generate any output.', $this->model));
    }
}
