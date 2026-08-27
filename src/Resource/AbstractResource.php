<?php

namespace OneToMany\AI\Resource;

use OneToMany\AI\Contract\Bridge\ProviderInterface;

/**
 * @template TProvider of ProviderInterface
 */
abstract readonly class AbstractResource
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
}
