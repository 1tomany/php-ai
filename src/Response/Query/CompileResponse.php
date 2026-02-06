<?php

namespace OneToMany\AI\Response\Query;

use OneToMany\AI\Request\Query\ExecuteRequest;
use OneToMany\AI\Response\BaseResponse;

final readonly class CompileResponse extends BaseResponse
{
    /**
     * @param non-empty-lowercase-string $model
     * @param non-empty-string $url
     * @param array<string, mixed> $request
     */
    public function __construct(
        string $model,
        private string $url,
        private array $request,
    ) {
        parent::__construct($model);
    }

    /**
     * @return non-empty-string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    public function toExecuteRequest(): ExecuteRequest
    {
        return new ExecuteRequest($this->getModel())->withUrl($this->getUrl())->withRequest($this->getRequest());
    }
}
