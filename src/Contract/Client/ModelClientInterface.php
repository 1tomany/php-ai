<?php

namespace OneToMany\AI\Contract\Client;

interface ModelClientInterface
{
    public function supportsRequest(object $request): bool;

    /**
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public function getSupportedModels(): array;
}
