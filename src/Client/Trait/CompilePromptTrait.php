<?php

namespace OneToMany\AI\Client\Trait;

use OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\CompiledPromptResponseInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Response\Prompt\CompiledPromptResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

trait CompilePromptTrait
{
    public function compile(CompilePromptRequestInterface $request): CompiledPromptResponseInterface
    {
        try {
            /** @var array<string, mixed> $compiledRequest */
            $compiledRequest = $this->normalizer->normalize($request);
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException(sprintf('Compiling the prompt for the model "%s" failed.', $request->getModel()), previous: $e);
        }

        return new CompiledPromptResponse($request->getVendor(), $request->getModel(), $compiledRequest);
    }
}
