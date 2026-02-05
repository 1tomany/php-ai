<?php

namespace OneToMany\AI\Client\Gemini\Trait;

use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface as HttpClientDecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface as HttpClientHttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface as HttpClientTransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait ExceptionTrait
{
    private function createErrorFromResponse(ResponseInterface $response): ErrorType
    {
        try {
            /**
             * @var array{
             *   error: array{
             *     code: non-negative-int,
             *     message: non-empty-string,
             *     status?: non-empty-string,
             *   },
             * } $error
             */
            $error = $response->toArray(false);
        } catch (HttpClientDecodingExceptionInterface) {
            return new ErrorType($response->getContent(false), $response->getStatusCode());
        }

        return new ErrorType($error['error']['message'], $error['error']['code']);
    }

    /**
     * @throws RuntimeException connecting to the server failed
     * @throws RuntimeException the server returned invalid JSON
     * @throws RuntimeException the server returned a 4xx or 5xx response
     */
    private function handleHttpException(HttpClientExceptionInterface $exception): never
    {
        if (
            $exception instanceof HttpClientTransportExceptionInterface
            || $exception instanceof HttpClientDecodingExceptionInterface
        ) {
            throw new RuntimeException($exception->getMessage(), previous: $exception);
        }

        if ($exception instanceof HttpClientHttpExceptionInterface) {
            throw new RuntimeException($this->createErrorFromResponse($exception->getResponse())->getMessage(), $exception->getResponse()->getStatusCode(), $exception);
        }

        throw new RuntimeException($exception->getMessage(), previous: $exception);
    }
}
