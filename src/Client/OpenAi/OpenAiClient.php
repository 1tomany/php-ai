<?php

namespace OneToMany\AI\Client\OpenAi;

use OneToMany\AI\Client\OpenAi\Type\Error\ErrorType;
use OneToMany\AI\Client\Trait\SupportsModelTrait;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function ltrim;
use function sprintf;

abstract readonly class OpenAiClient
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

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     */
    protected function generateUrl(string $path): string
    {
        return sprintf('https://api.openai.com/v1/%s', ltrim($path, '/'));
    }

    protected function decodeErrorResponse(ResponseInterface $response): ErrorType
    {
        try {
            /**
             * @var array{
             *   error: array{
             *     message: non-empty-string,
             *     type?: ?non-empty-string,
             *     param?: ?non-empty-string,
             *     code?: ?non-empty-string,
             *   },
             * } $error
             */
            $error = $response->toArray(false);
        } catch (HttpClientExceptionInterface) {
            return new ErrorType($response->getContent(false));
        }

        return new ErrorType($error['error']['message'], $error['error']['type'] ?? null, $error['error']['param'] ?? null, $error['error']['code'] ?? null);
    }
}
