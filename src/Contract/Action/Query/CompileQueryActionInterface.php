<?php

namespace OneToMany\AI\Contract\Action\Query;

interface CompileQueryActionInterface
{
    public function act(CompileRequest $request): CompileResponse;
}
