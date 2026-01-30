<?php

namespace OneToMany\AI\Contract\Client;

use OneToMany\AI\Contract\Input\Request\CompilePromptRequestInterface;
use OneToMany\AI\Contract\Input\Request\DispatchPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\CompiledPromptResponseInterface;
use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;

interface PromptClientInterface
{
    public function compile(CompilePromptRequestInterface $request): CompiledPromptResponseInterface;

    public function dispatch(DispatchPromptRequestInterface $request): DispatchedPromptResponseInterface;
}
