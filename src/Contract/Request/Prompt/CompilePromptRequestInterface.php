<?php

namespace OneToMany\AI\Contract\Request\Prompt;

use OneToMany\AI\Contract\Request\Prompt\Content\ContentInterface;

interface CompilePromptRequestInterface
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
     * @return list<ContentInterface>
     */
    public function getContents(): array;

    public function addContent(ContentInterface $content): static;
}
