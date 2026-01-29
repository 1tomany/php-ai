<?php

namespace App\Prompt\Vendor\Model\Response\Prompt;

use App\Prompt\Vendor\Model\Contract\Response\Prompt\SentPromptResponseInterface;

final readonly class SentPromptResponse implements SentPromptResponseInterface
{
    /**
     * @param non-empty-lowercase-string $model
     * @param non-empty-string $uri
     * @param ?non-empty-string $text
     * @param array<string, mixed> $response
     * @param non-negative-int|float $runtime
     */
    public function __construct(
        public string $model,
        public string $uri,
        public ?string $text,
        public array $response,
        public int|float $runtime = 0,
    ) {
    }

    /**
     * @see App\Prompt\Vendor\Model\Contract\Response\Prompt\SentPromptResponseInterface
     */
    public function __invoke(): array
    {
        return $this->response;
    }
}
