<?php

namespace App\File\Vendor\AI\Client\Mock;

use App\File\Vendor\AI\Client\Mock\Trait\GenerateUriTrait;

abstract readonly class BaseClient
{
    use GenerateUriTrait;

    protected \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @see App\File\Vendor\AI\Contract\Client\ModelClientInterface
     *
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public function getSupportedModels(): array
    {
        return ['mock'];
    }
}
