<?php

namespace OneToMany\AI\Bridge\Gemini\Normalizer;

use OneToMany\AI\Bridge\Gemini\Resource\Interaction\FileContent;
use OneToMany\AI\Bridge\Gemini\Resource\Interaction\TextContent;
use OneToMany\AI\Bridge\Gemini\Resource\Interaction\TextResponseFormat;
use OneToMany\AI\Resource\Prompt\IndexSearchTool;
use OneToMany\AI\Resource\Prompt\InputText;
use OneToMany\AI\Resource\Prompt\Prompt;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function array_replace;

final readonly class PromptNormalizer implements NormalizerInterface
{
    /**
     * @see Symfony\Component\Serializer\Normalizer\NormalizerInterface
     *
     * @param Prompt $prompt
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function normalize(
        mixed $prompt,
        ?string $format = null,
        array $context = [],
    ): array {
        $payload = [
            'model' => $prompt->getModelId(),
        ];

        foreach ($prompt->getInputs() as $input) {
            if (!isset($payload['input'])) {
                $payload['input'] = [];
            }

            if ($input instanceof InputText) {
                $payload['input'][] = new TextContent(
                    text: $input->getText(),
                );
            } else {
                $payload['input'][] = FileContent::create(
                    $input->getId(), $input->getType(),
                );
            }
        }

        if (null !== $instructions = $prompt->getInstructions()) {
            $payload['system_instruction'] = $instructions->getText();
        }

        if (null !== $schema = $prompt->getSchema()?->getSchema()) {
            $payload['response_format'] = new TextResponseFormat($schema);
        }

        foreach ($prompt->getTools() as $tool) {
            if ($tool instanceof IndexSearchTool) {
                $payload['tools'][] = [
                    'type' => 'file_search',
                    'file_search_store_names' => $tool->getIndexIds(),
                ];
            }
        }

        return array_replace($prompt->getOptions(), $payload);
    }

    /**
     * @see Symfony\Component\Serializer\Normalizer\NormalizerInterface
     */
    #[\Override]
    public function supportsNormalization(
        mixed $data,
        ?string $format = null,
        array $context = [],
    ): bool {
        return $data instanceof Prompt && $data->getModel()->getVendor()->isGemini();
    }

    /**
     * @see Symfony\Component\Serializer\Normalizer\NormalizerInterface
     */
    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return [
            Prompt::class => false,
        ];
    }
}
