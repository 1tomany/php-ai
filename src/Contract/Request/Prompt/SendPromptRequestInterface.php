<?php

namespace OneToMany\AI\Contract\Request\Prompt;

interface SendPromptRequestInterface
{
    /**
     * @var non-empty-lowercase-string
     */
    public string $vendor { get; }

    /**
     * @var non-empty-lowercase-string
     */
    public string $model { get; }

    /**
     * @var array<string, mixed>
     */
    public array $request { get; }
}
