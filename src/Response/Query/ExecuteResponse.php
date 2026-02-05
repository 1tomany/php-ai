<?php

namespace OneToMany\AI\Response\Query;

use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Response\BaseResponse;

use function json_decode;
use function max;

use const JSON_BIGINT_AS_STRING;
use const JSON_THROW_ON_ERROR;

final readonly class ExecuteResponse extends BaseResponse
{
    /**
     * @param non-empty-lowercase-string $model
     * @param non-empty-string $uri
     * @param non-empty-string $output
     * @param array<string, mixed> $response
     * @param non-negative-int|float $runtime
     */
    public function __construct(
        string $model,
        private string $uri,
        private string $output,
        private array $response,
        private int|float $runtime,
    ) {
        parent::__construct($model);
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return non-empty-string
     */
    public function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @return list<array<string, mixed>>|array<string, mixed>
     *
     * @throws RuntimeException the output is not valid JSON
     */
    public function toRecord(): array
    {
        try {
            /** @var list<array<string, mixed>>|array<string, mixed> $record */
            $record = json_decode($this->output, true, 512, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RuntimeException('Converting the output to a record failed.', previous: $e);
        }

        return $record;
    }

    /**
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @return non-negative-int
     */
    public function getRuntime(): int
    {
        return max(0, (int) $this->runtime);
    }
}
