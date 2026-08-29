<?php

namespace OneToMany\AI\Bridge\Common\Trait;

use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Resource\Query\Prompt;
use OneToMany\AI\Resource\Query\Query;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

trait PromptTrait
{
    /**
     * @see OneToMany\AI\Contract\Bridge\PromptProviderInterface
     *
     * @throws RuntimeException when compiling the prompt fails
     */
    #[\Override]
    public function compile(Prompt $prompt): Query
    {
        try {
            /** @var array<string, mixed> $payload */
            $payload = $this->serializer->normalize($prompt);
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException(sprintf('Compiling the prompt into a query for the model "%s" failed.', $prompt->getModel()->getId()), previous: $e);
        }

        return new Query($prompt->getModel(), $payload);
    }
}
