<?php

namespace OneToMany\AI\Contract\Client;

use OneToMany\AI\Request\Query\CompileRequest;
use OneToMany\AI\Request\Query\ExecuteRequest;
use OneToMany\AI\Response\Query\CompileResponse;
use OneToMany\AI\Response\Query\ExecuteResponse;

interface QueryClientInterface extends ClientInterface
{
    public function compile(CompileRequest $request): CompileResponse;

    public function execute(ExecuteRequest $request): ExecuteResponse;
}
