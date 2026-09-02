<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\ProviderInterface;
use OneToMany\AI\ModelVendor;

/**
 * @template TProvider of ProviderInterface
 */
abstract readonly class Resources
{
    /**
     * @var Registry<TProvider>
     */
    protected Registry $providers;

    /**
     * @param iterable<TProvider> $providers
     */
    public function __construct(iterable $providers)
    {
        $this->providers = new Registry($providers);
    }

    /**
     * @return TProvider
     */
    protected function getProvider(string|ModelVendor $vendor): ProviderInterface
    {
        return $this->providers->get(ModelVendor::create($vendor));
    }
}
