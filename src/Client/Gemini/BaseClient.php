<?php

namespace OneToMany\AI\Client\Gemini;

use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract readonly class BaseClient
{
    public function __construct(protected HttpClientInterface $httpClient)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Client\ModelClientInterface
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
     * @param non-empty-string $model
     *
     * @return non-empty-string
     */
    protected function generateUrl(string $path): string
    {
        return \sprintf('https://generativelanguage.googleapis.com/%s', \ltrim($path, '/'));
    }
}
