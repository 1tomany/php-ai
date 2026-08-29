<?php

namespace OneToMany\AI\Contract\Resource;

use OneToMany\AI\Resource\Prompt\Prompt;
use OneToMany\AI\Resource\Prompt\Query;
use OneToMany\AI\Resource\Prompt\Response;

interface PromptsInterface
{
    public function compile(Prompt $prompt): Query;

    public function send(Prompt|Query $request): Response;
}
