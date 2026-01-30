<?php

namespace OneToMany\AI\Contract\Action\Request;

use OneToMany\AI\Contract\Input\Request\DispatchPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;

interface DispatchRequestActionInterface
{
    public function act(DispatchPromptRequestInterface $request): DispatchedPromptResponseInterface;
}
