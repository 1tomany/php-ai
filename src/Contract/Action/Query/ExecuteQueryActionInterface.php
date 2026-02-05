<?php

namespace OneToMany\AI\Contract\Action\Query;

interface ExecuteQueryActionInterface
{
    public function act(ExecuteRequest $request): ExecuteResponse;
}
