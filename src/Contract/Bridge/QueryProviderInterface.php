<?php

namespace OneToMany\AI\Contract\Bridge;

use OneToMany\AI\Model;
use OneToMany\AI\Resource\Query\Prompt;
use OneToMany\AI\Resource\Query\Query;
use OneToMany\AI\Resource\Query\Response;

interface QueryProviderInterface extends ProviderInterface
{
    public function compile(Model $model, Prompt $prompt): Query;

    public function run(Query $query): Response;
}
