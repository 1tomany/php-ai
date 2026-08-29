<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Resource\Query\Prompt;
use OneToMany\AI\Resource\Query\Query;
use OneToMany\AI\Resource\Query\Response;

interface QueriesInterface
{
    public function compile(Prompt $prompt): Query;

    public function send(Prompt|Query $request): Response;
}
