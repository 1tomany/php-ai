<?php

namespace OneToMany\AI\Action\Query;

use App\File\Vendor\AI\Contract\Action\Query\ExecuteQueryActionInterface;
use App\File\Vendor\AI\Factory\QueryClientFactory;
use App\File\Vendor\AI\Request\Query\ExecuteRequest;
use App\File\Vendor\AI\Response\Query\ExecuteResponse;

final readonly class ExecuteQueryAction implements ExecuteQueryActionInterface
{
    public function __construct(private QueryClientFactory $queryClientFactory)
    {
    }

    /**
     * @see App\File\Vendor\AI\Contract\Action\Query\CompileQueryActionInterface
     */
    public function act(ExecuteRequest $request): ExecuteResponse
    {
        return $this->queryClientFactory->create($request)->execute($request);
    }
}
