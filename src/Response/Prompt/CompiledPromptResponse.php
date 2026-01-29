<?php

namespace App\Prompt\Vendor\Model\Response\Prompt;

use App\Prompt\Vendor\Model\Contract\Response\Prompt\CompiledPromptResponseInterface;

final readonly class CompiledPromptResponse implements CompiledPromptResponseInterface
{
    /**
     * @param non-empty-lowercase-string $vendor
     * @param non-empty-lowercase-string $model
     * @param array<string, mixed> $request
     */
    public function __construct(
        public string $vendor,
        public string $model,
        public array $request,
    ) {
    }
}
