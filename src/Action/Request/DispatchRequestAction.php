<?php

namespace OneToMany\AI\Action\Request;

use OneToMany\AI\Contract\Action\Request\DispatchRequestActionInterface;
use OneToMany\AI\Contract\Input\Request\DispatchPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;
use OneToMany\AI\Factory\PromptClientFactory;

final readonly class DispatchRequestAction implements DispatchRequestActionInterface
{
    public function __construct(private PromptClientFactory $promptClientFactory)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\Request\DispatchRequestActionInterface
     */
    public function act(DispatchPromptRequestInterface $request): DispatchedPromptResponseInterface
    {
        return $this->promptClientFactory->create($request->getVendor())->dispatch($request);
    }
}
