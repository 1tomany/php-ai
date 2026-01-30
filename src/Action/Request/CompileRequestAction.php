<?php

namespace OneToMany\AI\Action\Request;

use OneToMany\AI\Contract\Action\Request\CompileRequestActionInterface;
use OneToMany\AI\Contract\Input\Request\CompilePromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\CompiledPromptResponseInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Factory\PromptClientFactory;

use function sprintf;

final readonly class CompileRequestAction implements CompileRequestActionInterface
{
    public function __construct(private PromptClientFactory $promptClientFactory)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\Request\CompileRequestActionInterface
     */
    public function act(CompilePromptRequestInterface $request): CompiledPromptResponseInterface
    {
        if (!$request->hasContents()) {
            throw new InvalidArgumentException(sprintf('Compiling the prompt for the model "%s" failed because the contents are empty.', $request->getModel()));
        }

        return $this->promptClientFactory->create($request->getVendor())->compile($request);
    }
}
