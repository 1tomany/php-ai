<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Model;
use OneToMany\AI\Resource\Query\Prompt;
use OneToMany\AI\Resource\Query\Query;
use OneToMany\AI\Resource\Query\Response;

interface QueriesInterface
{
    public function compile(string|Model $model, Prompt $prompt): Query;

    public function send(Query $query): Response;

    public function compileAndRun(string|Model $model, Prompt $prompt): Response;
}
