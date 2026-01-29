<?php

namespace OneToMany\AI\Action\Prompt;

use OneToMany\AI\Contract\Action\Prompt\CompilePromptActionInterface;
use OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\CompiledPromptResponseInterface;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Response\Prompt\CompiledPromptResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class CompilePromptAction implements CompilePromptActionInterface
{
    public function __construct(private NormalizerInterface $normalizer)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\Prompt\CompilePromptActionInterface
     */
    public function act(CompilePromptRequestInterface $request): CompiledPromptResponseInterface
    {
        try {
            /** @var array<string, mixed> $requestContent */
            $requestContent = $this->normalizer->normalize($request);
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException('Compiling the prompt failed.', previous: $e);
        }

        return new CompiledPromptResponse($request->getVendor(), $request->getModel(), $requestContent);
    }
}
