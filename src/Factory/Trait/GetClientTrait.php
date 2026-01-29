<?php

namespace OneToMany\AI\Factory\Trait;

use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Contract\Client\PromptClientInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function is_a;
use function is_object;
use function sprintf;

/**
 * @property ContainerInterface $clients
 */
trait GetClientTrait
{
    /**
     * @template T of FileClientInterface|PromptClientInterface
     *
     * @param non-empty-string $vendor
     * @param class-string<T> $clientType
     *
     * @return T
     */
    private function getClient(string $vendor, string $clientType): object
    {
        try {
            $client = $this->clients->get($vendor);
        } catch (ContainerExceptionInterface $e) {
        }

        if (!isset($client) || !is_object($client) || !is_a($client, $clientType, true)) {
            throw new InvalidArgumentException(sprintf('A client for the vendor "%s" could not be found.', $vendor), previous: $e ?? null);
        }

        return $client;
    }
}
