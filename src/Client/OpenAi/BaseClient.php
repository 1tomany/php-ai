<?php

namespace OneToMany\AI\Client\OpenAi;

use OneToMany\AI\Client\Trait\SupportsModelTrait;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract readonly class BaseClient
{
    use SupportsModelTrait;

    /**
     * @param non-empty-string $apiKey
     */
    public function __construct(
        protected HttpClientInterface $httpClient,
        #[\SensitiveParameter] protected string $apiKey,
    ) {
    }

    /**
     * @see OneToMany\AI\Contract\Client\ClientInterface
     *
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public function getSupportedModels(): array
    {
        return [
            'gpt-5.2-pro',
            'gpt-5.2-pro-2025-12-11',
            'gpt-5.2',
            'gpt-5.2-2025-12-11',
            'gpt-5.1',
            'gpt-5.1-2025-11-13',
            'gpt-5-pro',
            'gpt-5-pro-2025-10-06',
            'gpt-5',
            'gpt-5-2025-08-07',
            'gpt-5-mini',
            'gpt-5-mini-2025-08-07',
            'gpt-5-nano',
            'gpt-5-nano-2025-08-07',
            'gpt-4.1',
            'gpt-4.1-2025-04-14',
        ];
    }
}
