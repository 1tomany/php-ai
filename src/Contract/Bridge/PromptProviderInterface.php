<?php

namespace OneToMany\AI\Contract\Bridge;

use OneToMany\AI\Resource\Prompt\Prompt;
use OneToMany\AI\Resource\Prompt\Query;
use OneToMany\AI\Resource\Prompt\Response;

interface PromptProviderInterface extends ProviderInterface
{
    public function compile(Prompt $prompt): Query;

    public function send(Query $query): Response;
}
