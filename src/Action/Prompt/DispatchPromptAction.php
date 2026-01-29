<?php

namespace OneToMany\AI\Action\Prompt;

use OneToMany\AI\Contract\Action\Prompt\DispatchPromptActionInterface;
use OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;
use OneToMany\AI\Factory\PromptClientFactory;

final readonly class DispatchPromptAction implements DispatchPromptActionInterface
{
    public function __construct(private PromptClientFactory $promptClientFactory)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\Prompt\DispatchPromptActionInterface
     */
    public function act(DispatchPromptRequestInterface $request): DispatchedPromptResponseInterface
    {
        return $this->promptClientFactory->create($request->getVendor())->dispatch($request);
    }
}
