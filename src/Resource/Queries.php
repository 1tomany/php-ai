<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\QueryProviderInterface;
use OneToMany\AI\Contract\Resource\QueriesInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Resource\Query\Prompt;
use OneToMany\AI\Resource\Query\Query;
use OneToMany\AI\Resource\Query\Response;

/**
 * @extends Resources<QueryProviderInterface>
 */
final readonly class Queries extends Resources implements QueriesInterface
{
    /**
     * @see OneToMany\AI\Contract\Resource\QueriesInterface
     *
     * @throws DomainException when the prompt has no input
     */
    #[\Override]
    public function compile(Prompt $prompt): Query
    {
        if ($prompt->isEmpty()) {
            throw new DomainException('At least one text or file input is required to compile a prompt into a query.');
        }

        return $this->getProvider($prompt->getModel()->getVendor())->compile($prompt);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\QueriesInterface
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
