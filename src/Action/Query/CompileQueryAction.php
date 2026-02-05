<?php

namespace OneToMany\AI\Action\Query;

final readonly class CompileQueryAction implements CompileQueryActionInterface
{
    public function __construct(private QueryClientFactory $queryClientFactory)
    {
    }

    /**
     * @see App\File\Vendor\AI\Contract\Action\Query\CompileQueryActionInterface
     *
     * @throws InvalidArgumentException the request does not have any components
     */
    public function act(CompileRequest $request): CompileResponse
    {
        if (!$request->hasComponents()) {
            throw new InvalidArgumentException('Compiling a query requires one more more components.');
        }

        return $this->queryClientFactory->create($request)->compile($request);
    }
}
