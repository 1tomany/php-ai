<?php

namespace OneToMany\AI\Client\Mock;

use OneToMany\AI\Client\Mock\Trait\GenerateUriTrait;
use OneToMany\AI\Contract\Client\PromptClientInterface;
use OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;
use OneToMany\AI\Response\Prompt\DispatchedPromptResponse;

use function json_encode;

final readonly class PromptClient implements PromptClientInterface
{
    use GenerateUriTrait;

    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function dispatch(DispatchPromptRequestInterface $request): DispatchedPromptResponseInterface
    {
        $uri = $this->generateUri('resp');

        /** @var non-empty-string $text */
        $text = json_encode(['tag' => $this->faker->word()]);

        return new DispatchedPromptResponse($request->getVendor(), $request->getModel(), $uri, $text, ['id' => $uri, 'text' => $text]);
    }
}
