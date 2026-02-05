<?php

namespace OneToMany\AI\Client\Trait;

use function in_array;

trait SupportsModelTrait
{
    /**
     * @see OneToMany\AI\Contract\Client\ClientInterface
     *
     * @param non-empty-lowercase-string $model
     */
    public function supportsModel(string $model): bool
    {
        return in_array($model, $this->getSupportedModels());
    }
}
