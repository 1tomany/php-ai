<?php

namespace OneToMany\AI\Client\Gemini;

use OneToMany\AI\Client\Gemini\Type\ErrorType;
use OneToMany\AI\Client\Trait\SupportsModelTrait;
use OneToMany\AI\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface as HttpClientHttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function ltrim;
use function sprintf;

abstract readonly class GeminiClient
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
            'gemini-3-pro-preview',
            'gemini-3-pro-image-preview',
            'gemini-3-flash-preview',
            'gemini-2.5-pro',
            'gemini-2.5-flash',
            'gemini-2.5-flash-preview-09-2025',
            'gemini-2.5-flash-image',
            'gemini-2.5-flash-lite',
            'gemini-2.5-flash-lite-preview-09-2025',
        ];
    }

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     */
    protected function generateUrl(string $path): string
    {
        return sprintf('https://generativelanguage.googleapis.com/%s', ltrim($path, '/'));
    }

    protected function decodeErrorResponse(ResponseInterface $response): ErrorType
    {
        try {
            /**
             * @var array{
             *   error: array{
             *     code: non-negative-int,
             *     message: non-empty-string,
             *     status: non-empty-string,
             *   },
             * } $error
             */
            $error = $response->toArray(false);
        } catch (HttpClientExceptionInterface) {
            return new ErrorType($response->getStatusCode(), $response->getContent(false));
        }

        return new ErrorType($error['error']['code'], $error['error']['message'], $error['error']['status']);
    }

    /**
     * @throws RuntimeException connecting to the server failed
     * @throws RuntimeException the server returned invalid JSON
     * @throws RuntimeException the server returned a 4xx or 5xx response
     */
    protected function handleHttpException(HttpClientExceptionInterface $exception): never
    {
        if ($exception instanceof HttpClientHttpExceptionInterface) {
            throw new RuntimeException($this->decodeErrorResponse($exception->getResponse())->getMessage(), $exception->getResponse()->getStatusCode(), $exception);
        }

        throw new RuntimeException($exception->getMessage(), previous: $exception);
    }
}
