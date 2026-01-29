<?php

namespace OneToMany\AI\Action\Prompt;

use OneToMany\AI\Contract\Action\Prompt\CompilePromptActionInterface;
use OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\CompiledPromptResponseInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Factory\PromptClientFactory;

use function sprintf;

final readonly class CompilePromptAction implements CompilePromptActionInterface
{
    public function __construct(private PromptClientFactory $promptClientFactory)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\Prompt\CompilePromptActionInterface
     */
    public function act(CompilePromptRequestInterface $request): CompiledPromptResponseInterface
    {
        if (!$request->hasContents()) {
            throw new InvalidArgumentException(sprintf('Compiling the prompt for the model "%s" failed because the contents are empty.', $request->getModel()));
        }

        return $this->promptClientFactory->create($request->getVendor())->compile($request);
    }
}
