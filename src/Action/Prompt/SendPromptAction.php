<?php

namespace OneToMany\AI\Action\Prompt;

use OneToMany\AI\Contract\Action\Prompt\SendPromptActionInterface;
use OneToMany\AI\Contract\Request\Prompt\SendPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\SentPromptResponseInterface;
use OneToMany\AI\Factory\PromptClientFactory;

final readonly class SendPromptAction implements SendPromptActionInterface
{
    public function __construct(private PromptClientFactory $promptClientFactory)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\Prompt\SendPromptActionInterface
     */
    public function act(SendPromptRequestInterface $request): SentPromptResponseInterface
    {
        return $this->promptClientFactory->create($request->getVendor())->send($request);
    }
}
