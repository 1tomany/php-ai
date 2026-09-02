<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\ProviderInterface;
use OneToMany\AI\Exception\DomainException;
use OneToMany\AI\ModelVendor;

use function sprintf;

/**
 * @template T of ProviderInterface
 */
final readonly class Registry
{
    /**
     * @var array<non-empty-lowercase-string, T>
     */
    private array $providers;

    /**
     * @param iterable<T> $providers
     *
     * @throws DomainException when a provider is already registered
     */
    public function __construct(iterable $providers)
    {
        $indexedProviders = [];

        foreach ($providers as $provider) {
            if (isset($indexedProviders[$provider::getVendor()->getValue()])) {
                throw new DomainException(sprintf('The "%s" provider is already registered.', $provider::getVendor()->getValue()));
            }

            $indexedProviders[$provider::getVendor()->getValue()] = $provider;
        }

        $this->providers = $indexedProviders;
    }

    /**
     * @return T
     *
     * @throws DomainException when a provider is not registered
     */
    public function get(ModelVendor $provider): ProviderInterface
    {
        if (!isset($this->providers[$provider->getValue()])) {
            throw new DomainException(sprintf('The "%s" provider is not registered.', $provider->getValue()));
        }

        return $this->providers[$provider->getValue()];
    }
}
