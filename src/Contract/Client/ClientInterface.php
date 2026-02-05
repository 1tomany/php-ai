<?php

namespace OneToMany\AI\Contract\Client;

interface ClientInterface
{
    /**
     * @param non-empty-lowercase-string $model
     */
    public function supportsModel(string $model): bool;

    /**
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public function getSupportedModels(): array;
}
