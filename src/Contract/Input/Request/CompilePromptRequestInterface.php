<?php

namespace OneToMany\AI\Contract\Input\Request;

use OneToMany\AI\Contract\Input\Request\Content\ContentInterface;

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

    public function hasContents(): bool;
}
