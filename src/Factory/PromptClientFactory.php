<?php

namespace OneToMany\AI\Factory;

use OneToMany\AI\Contract\Client\PromptClientInterface;
use OneToMany\AI\Factory\Trait\GetClientTrait;
use Psr\Container\ContainerInterface;

final readonly class PromptClientFactory
{
    use GetClientTrait;

    public function __construct(private ContainerInterface $clients)
    {
    }

    /**
     * @param non-empty-string $vendor
     */
    public function create(string $vendor): PromptClientInterface
    {
        return $this->getClient($vendor, PromptClientInterface::class);
    }
}
