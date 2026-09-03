<?php

namespace OneToMany\AI\Bridge\Gemini;

use OneToMany\AI\Bridge\Transport;
use OneToMany\AI\Contract\Bridge\ProviderInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\ModelVendor;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

use function sprintf;
use function trim;

abstract readonly class AbstractProvider implements ProviderInterface
{
    /**
     * @var non-empty-string
     */
    protected string $apiKey;

    /**
     * @var non-empty-string
     */
    protected string $baseUrl;

    /**
     * @var non-empty-string
     */
    protected string $apiVersion;

    /**
     * @throws DomainException when the Gemini API key is empty
     */
    public function __construct(
        protected Transport $transport,
        protected SerializerInterface&DenormalizerInterface&NormalizerInterface $serializer,
        #[\SensitiveParameter] string $apiKey,
        ?string $baseUrl = null,
        ?string $apiVersion = null,
    ) {
        if ('' === $apiKey = trim($apiKey)) {
            throw new DomainException(sprintf('The %s API key cannot be empty.', $this->getVendor()->getName()));
        }

        $this->apiKey = $apiKey;

        if ('' === $baseUrl = trim((string) $baseUrl)) {
            $baseUrl = 'https://generativelanguage.googleapis.com';
        }

        $this->baseUrl = $baseUrl;

        if ('' === $apiVersion = trim((string) $apiVersion)) {
            $apiVersion = 'v1beta';
        }

        $this->apiVersion = $apiVersion;
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public static function getVendor(): ModelVendor
    {
        return ModelVendor::Gemini;
    }

    protected function url(string ...$parts): string
    {
        return $this->transport->url($this->baseUrl, ...$parts);
    }
}
