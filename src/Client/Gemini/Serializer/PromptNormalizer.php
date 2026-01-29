<?php

namespace OneToMany\AI\Client\Gemini\Serializer;

use OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface;
use OneToMany\AI\Request\Prompt\Content\CachedFile;
use OneToMany\AI\Request\Prompt\Content\InputText;
use OneToMany\AI\Request\Prompt\Content\JsonSchema;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function in_array;

/**
 * @phpstan-type GeminiPromptFileUri array{
 *   fileData: array{
 *     fileUri: non-empty-string,
 *   },
 * }
 * @phpstan-type GeminiPromptInputText array{
 *   text: non-empty-string,
 * }
 */
final readonly class PromptNormalizer implements NormalizerInterface
{
    /**
     * @see Symfony\Component\Serializer\Normalizer\NormalizerInterface
     *
     * @param CompilePromptRequestInterface $data
     *
     * @return array{
     *   systemInstruction?: array{
     *     parts: non-empty-list<GeminiPromptInputText>,
     *     role: 'system',
     *   },
     *   contents: list<
     *     array{
     *       parts: non-empty-list<GeminiPromptInputText|GeminiPromptFileUri>,
     *       role: 'user'|'system',
     *     },
     *   >,
     *   generationConfig?: array{
     *     responseJsonSchema: array<string, mixed>,
     *     responseMimeType: non-empty-lowercase-string,
     *   },
     * }
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $requestContent = ['contents' => []];

        foreach ($data->getContents() as $content) {
            if ($content instanceof InputText) {
                if ($content->getRole()->isSystem()) {
                    $requestContent['systemInstruction'] = [
                        'parts' => [
                            [
                                'text' => $content->getText(),
                            ],
                        ],
                        'role' => $content->getRole()->getValue(),
                    ];
                }

                if ($content->getRole()->isUser()) {
                    $requestContent['contents'][] = [
                        'parts' => [
                            [
                                'text' => $content->getText(),
                            ],
                        ],
                        'role' => $content->getRole()->getValue(),
                    ];
                }
            }

            if ($content instanceof CachedFile) {
                $requestContent['contents'][] = [
                    'parts' => [
                        [
                            'fileData' => [
                                'fileUri' => $content->getUri(),
                            ],
                        ],
                    ],
                    'role' => $content->getRole()->getValue(),
                ];
            }

            if ($content instanceof JsonSchema) {
                $requestContent['generationConfig'] = [
                    'responseJsonSchema' => $content->getSchema(),
                    'responseMimeType' => $content->getFormat(),
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
        return $data instanceof CompilePromptRequestInterface && in_array($data->getVendor(), ['gemini']);
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
