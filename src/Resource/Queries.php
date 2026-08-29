<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\QueryProviderInterface;
use OneToMany\AI\Contract\Resource\QueriesInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\Model;
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
    public function compile(
        string|Model $model,
        Prompt $prompt,
    ): Query {
        $model = Model::create($model);

        if ($prompt->isEmpty()) {
            throw new DomainException('At least one text or file input is required to compile a prompt into a query.');
        }

        return $this->getProvider($model->getVendor())->compile($model, $prompt);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\QueriesInterface
     */
    #[\Override]
    public function send(Query $query): Response
    {
        return $this->getProvider($query->getModel()->getVendor())->run($query);
    }

    /**
     * @see OneToMany\AI\Contract\Resource\QueriesInterface
     */
    #[\Override]
    public function compileAndRun(
        string|Model $model,
        Prompt $prompt,
    ): Response {
        return $this->send($this->compile(Model::create($model), $prompt));
    }
}
