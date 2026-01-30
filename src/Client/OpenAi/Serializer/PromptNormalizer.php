<?php

namespace OneToMany\AI\Client\OpenAi\Serializer;

use OneToMany\AI\Contract\Input\Request\CompilePromptRequestInterface;
use OneToMany\AI\Request\Prompt\Content\CachedFile;
use OneToMany\AI\Request\Prompt\Content\InputText;
use OneToMany\AI\Request\Prompt\Content\JsonSchema;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function in_array;

/**
 * @phpstan-type OpenAiContentFileUri array{
 *   type: 'input_file',
 *   file_id: non-empty-string,
 * }
 * @phpstan-type OpenAiContentInputText array{
 *   type: 'input_text',
 *   text: non-empty-string,
 * }
 * @phpstan-type OpenAiPrompt array{
 *   input?: non-empty-list<
 *     array{
 *       content: non-empty-list<OpenAiContentFileUri|OpenAiContentInputText>,
 *       role: 'system'|'user',
 *     },
 *   >,
 *   text?: array{
 *     format: array{
 *       type: 'json_schema',
 *       name: non-empty-lowercase-string,
 *       schema: array<string, mixed>,
 *       strict: bool,
 *     },
 *   },
 * }
 */
final readonly class PromptNormalizer implements NormalizerInterface
{
    /**
     * @see Symfony\Component\Serializer\Normalizer\NormalizerInterface
     *
     * @param CompilePromptRequestInterface $data
     *
     * @return OpenAiPrompt
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $requestContent = [
            'model' => $data->getModel(),
        ];

        foreach ($data->getContents() as $content) {
            if ($content instanceof InputText) {
                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => $content->getText(),
                        ],
                    ],
                    'role' => $content->getRole()->getValue(),
                ];
            }

            if ($content instanceof CachedFile) {
                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => 'input_file',
                            'file_id' => $content->getUri(),
                        ],
                    ],
                    'role' => $content->getRole()->getValue(),
                ];
            }

            if ($content instanceof JsonSchema) {
                $requestContent['text'] = [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => $content->getName(),
                        'schema' => $content->getSchema(),
                        'strict' => $content->isStrict(),
                    ],
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
        return $data instanceof CompilePromptRequestInterface && in_array($data->getVendor(), ['openai']);
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
