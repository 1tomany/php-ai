<?php

namespace OneToMany\AI\Client\Mock\Serializer;

use OneToMany\AI\Contract\Input\Request\CompilePromptRequestInterface;
use OneToMany\AI\Request\Prompt\Content\CachedFile;
use OneToMany\AI\Request\Prompt\Content\InputText;
use OneToMany\AI\Request\Prompt\Content\JsonSchema;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function in_array;

/**
 * @phpstan-type MockContentFileUri array{
 *   fileUri: non-empty-string,
 *   format: non-empty-lowercase-string,
 * }
 * @phpstan-type MockContentInputText array{
 *   text: non-empty-string,
 *   role: non-empty-string,
 * }
 * @phpstan-type MockContentJsonSchema array{
 *   name: non-empty-string,
 *   schema: array<string, mixed>,
 *   format: non-empty-lowercase-string,
 * }
 * @phpstan-type MockPrompt array{
 *   contents: list<MockContentFileUri|MockContentInputText|MockContentJsonSchema>,
 * }
 */
final readonly class PromptNormalizer implements NormalizerInterface
{
    /**
     * @see Symfony\Component\Serializer\Normalizer\NormalizerInterface
     *
     * @param CompilePromptRequestInterface $data
     *
     * @return MockPrompt
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $requestContent = ['contents' => []];

        foreach ($data->getContents() as $content) {
            if ($content instanceof InputText) {
                $requestContent['contents'][] = [
                    'text' => $content->getText(),
                    'role' => $content->getRole()->getValue(),
                ];
            }

            if ($content instanceof CachedFile) {
                $requestContent['contents'][] = [
                    'fileUri' => $content->getUri(),
                    'format' => $content->getFormat(),
                ];
            }

            if ($content instanceof JsonSchema) {
                $requestContent['schema'] = [
                    'name' => $content->getName(),
                    'schema' => $content->getSchema(),
                    'format' => $content->getFormat(),
                ];
            }
        }

        return $requestContent;
    }

    /**
     * @see Symfony\Component\Serializer\Normalizer\NormalizerInterface
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof CompilePromptRequestInterface && in_array($data->getVendor(), ['mock']);
    }

    /**
     * @see Symfony\Component\Serializer\Normalizer\NormalizerInterface
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            CompilePromptRequestInterface::class => true,
        ];
    }
}
