<?php

namespace OneToMany\AI\Client\Trait;

use OneToMany\AI\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface as HttpClientHttpExceptionInterface;

trait HttpExceptionTrait
{
    /**
     * Decodes, wraps, and throws any exception thrown by the Symfony HTTP Client.
     *
     * @throws RuntimeException
     */
    protected function handleHttpException(HttpClientExceptionInterface $exception): never
    {
        if ($exception instanceof HttpClientHttpExceptionInterface) {
            throw new RuntimeException($this->decodeErrorResponse($exception->getResponse())->getMessage(), $exception->getResponse()->getStatusCode(), $exception);
        }

        throw new RuntimeException($exception->getMessage(), previous: $exception);
    }
}
