<?php

namespace OneToMany\AI\Contract\Request\Prompt;

interface DispatchPromptRequestInterface
{
    /**
     * @return non-empty-lowercase-string
     */
    public function getVendor(): string;

    /**
     * @return non-empty-lowercase-string
     */
    public function getModel(): string;

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array;
}
