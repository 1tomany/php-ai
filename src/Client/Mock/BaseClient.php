<?php

namespace OneToMany\AI\Client\Mock;

use OneToMany\AI\Client\Mock\Trait\GenerateUriTrait;
use OneToMany\AI\Client\Trait\SupportsModelTrait;

abstract readonly class BaseClient
{
    use GenerateUriTrait;
    use SupportsModelTrait;

    protected \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @see OneToMany\AI\Contract\Client\ClientInterface
     *
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public function getSupportedModels(): array
    {
        return ['mock'];
    }
}
