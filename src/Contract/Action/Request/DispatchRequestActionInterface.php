<?php

namespace OneToMany\AI\Contract\Action\Request;

use OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;

interface DispatchRequestActionInterface
{
    public function act(DispatchPromptRequestInterface $request): DispatchedPromptResponseInterface;
}
