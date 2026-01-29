<?php

namespace OneToMany\AI\Contract\Action\Prompt;

use OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;

interface DispatchPromptActionInterface
{
    public function act(DispatchPromptRequestInterface $request): DispatchedPromptResponseInterface;
}
