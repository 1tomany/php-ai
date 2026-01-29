<?php

namespace OneToMany\AI\Factory;

use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Factory\Trait\GetClientTrait as TraitGetClientTrait;
use Psr\Container\ContainerInterface;

final readonly class FileClientFactory
{
    use TraitGetClientTrait;

    public function __construct(private ContainerInterface $clients)
    {
    }

    /**
     * @param non-empty-string $vendor
     */
    public function create(string $vendor): FileClientInterface
    {
        return $this->getClient($vendor, FileClientInterface::class);
    }
}
