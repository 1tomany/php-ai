<?php

namespace OneToMany\AI\Client\Gemini;

use OneToMany\AI\Exception\InvalidArgumentException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract readonly class AbstractGeminiClient
{
    protected HttpClientInterface $httpClient;
    protected DenormalizerInterface $denormalizer;

    /**
     * @param ?non-empty-string $geminiApiKey
     *
     * @throws InvalidArgumentException both the `$geminiApiKey` and `$httpClient` arguments are null
     */
    public function __construct(
        ?string $geminiApiKey,
        ?HttpClientInterface $httpClient,
        DenormalizerInterface $denormalizer,
    ) {
        $geminiApiKey = trim($geminiApiKey ?? '');

        if (empty($geminiApiKey) && null === $httpClient) {
            throw new InvalidArgumentException('Constructing the Gemini file client requires either an API key or scoped HTTP client, but neither were provided.');
        }

        if (null === $httpClient) {
            $httpClient = HttpClient::create([
                'headers' => [
                    'accept' => 'application/json',
                    'x-goog-api-key' => $geminiApiKey,
                ],
            ]);
        }

        $this->httpClient = $httpClient;
        $this->denormalizer = $denormalizer;
    }
}
