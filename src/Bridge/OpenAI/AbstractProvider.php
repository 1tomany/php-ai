<?php

namespace OneToMany\AI\Bridge\OpenAI;

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
    public const string BASE_URL = 'https://api.openai.com';

    /**
     * @throws DomainException when the OpenAI API key is empty
     */
    public function __construct(
        protected Transport $transport,
        protected SerializerInterface&DenormalizerInterface&NormalizerInterface $serializer,
        #[\SensitiveParameter] protected string $apiKey,
        protected string $apiVersion = 'v1',
    ) {
        if ('' === $this->apiKey) {
            throw new DomainException(sprintf('The %s API key cannot be empty.', static::getVendor()->getName()));
        }
    }

    /**
     * @see OneToMany\AI\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public static function getVendor(): ModelVendor
    {
        return ModelVendor::OpenAI;
    }

    protected function url(string ...$parts): string
    {
        return $this->transport->url(self::BASE_URL, $this->apiVersion, ...$parts);
    }
}
