<?php

namespace OneToMany\AI\Client\Gemini;

use OneToMany\AI\Exception\InvalidArgumentException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function trim;

abstract readonly class BaseClient
{
    protected HttpClientInterface $httpClient;

    /**
     * @param ?non-empty-string $geminiApiKey
     *
     * @throws InvalidArgumentException both the `$geminiApiKey` and `$httpClient` arguments are null
     */
    public function __construct(
        ?string $geminiApiKey,
        ?HttpClientInterface $httpClient,
        protected NormalizerInterface&DenormalizerInterface $normalizer,
    ) {
        $geminiApiKey = trim($geminiApiKey ?? '');

        if (empty($geminiApiKey) && null === $httpClient) {
            throw new InvalidArgumentException('Constructing a Gemini client requires either an API key or scoped HTTP client, but neither were provided.');
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
    }
}
