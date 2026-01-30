<?php

namespace OneToMany\AI\Contract\Input\Request;

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
