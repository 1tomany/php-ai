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

abstract readonly class AbstractProvider implements ProviderInterface
{
    /**
     * @var non-empty-string
     */
    protected string $apiKey;

    /**
     * @throws DomainException when the Gemini API key is empty
     */
    public function __construct(
        protected Transport $transport,
        protected SerializerInterface&DenormalizerInterface&NormalizerInterface $serializer,
        #[\SensitiveParameter] string $apiKey,
        protected string $baseUrl = 'https://generativelanguage.googleapis.com',
        protected string $apiVersion = 'v1beta',
    ) {
        if ('' === $apiKey = \trim($apiKey)) {
            throw new DomainException(sprintf('The %s API key cannot be empty.', $this->getVendor()->getName()));
        }

        $this->apiKey = $apiKey;
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
