<?php

namespace OneToMany\AI\Contract\Request\Prompt;

use App\Prompt\Vendor\Model\Contract\Prompt\PromptContentInterface;

interface CompilePromptRequestInterface
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
     * @var list<PromptContentInterface>
     */
    public array $contents { get; }
}
