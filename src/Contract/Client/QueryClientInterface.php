<?php

namespace OneToMany\AI\Contract\Client;

interface QueryClientInterface extends ModelClientInterface
{
    public function compile(CompileRequest $request): CompileResponse;

    public function execute(ExecuteRequest $request): ExecuteResponse;
}
