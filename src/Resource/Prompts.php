<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\PromptProviderInterface;
use OneToMany\AI\Contract\Resource\PromptsInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\Prompt\Prompt;
use OneToMany\AI\Resource\Prompt\Query;
use OneToMany\AI\Resource\Prompt\Response;

/**
 * @extends Resources<PromptProviderInterface>
 */
final readonly class Prompts extends Resources implements PromptsInterface
{
    /**
     * @see OneToMany\AI\Contract\Resource\PromptsInterface
     *
     * @throws DomainException when the prompt has no input
     */
    #[\Override]
    public function compile(Prompt $prompt): Query
    {
        if ($prompt->isEmpty()) {
            throw new DomainException('At least one input is required to compile a prompt into a query.');
        }

        return $this->getProvider($prompt->getModel()->getVendor())->compile($prompt);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\PromptsInterface
     */
    #[\Override]
    public function send(Prompt|Query $request): Response
    {
        if ($request instanceof Prompt) {
            $request = $this->compile($request);
        }

        return $this->getProvider($request->getModel()->getVendor())->send($request);
    }
}
