<?php

namespace OneToMany\AI\Contract\Action\Request;

use OneToMany\AI\Contract\Input\Request\CompilePromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\CompiledPromptResponseInterface;

interface CompileRequestActionInterface
{
    public function act(CompilePromptRequestInterface $request): CompiledPromptResponseInterface;
}
