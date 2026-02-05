<?php

namespace OneToMany\AI\Factory;

use function array_find;
use function array_values;
use function get_debug_type;
use function iterator_to_array;
use function sprintf;

final readonly class QueryClientFactory
{
    /**
     * @var list<QueryClientInterface>
     */
    private array $clients;

    /**
     * @param list<QueryClientInterface>|\Traversable<int|string, QueryClientInterface> $clients
     */
    public function __construct(iterable $clients)
    {
        $this->clients = array_values($clients instanceof \Traversable ? iterator_to_array($clients) : $clients);
    }

    /**
     * @throws InvalidArgumentException a client that implements `QueryClientInterface` cannot be found
     */
    public function create(object $request): QueryClientInterface
    {
        $client = array_find($this->clients, fn ($c) => $c->supportsRequest($request));

        if (!$client instanceof QueryClientInterface) {
            throw new UnexpectedTypeException(sprintf('Expected a client of type "%s", "%s" found for the request "%s".', QueryClientInterface::class, get_debug_type($client), $request::class));
        }

        return $client;
    }
}
