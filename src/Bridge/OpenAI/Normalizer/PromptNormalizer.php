<?php

namespace OneToMany\AI\Bridge\OpenAI\Normalizer;

use OneToMany\AI\Bridge\OpenAI\Resource\Response\EasyInputMessage;
use OneToMany\AI\Bridge\OpenAI\Resource\Response\ResponseInput;
use OneToMany\AI\Resource\Query\InputText;
use OneToMany\AI\Resource\Query\Prompt;
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
    ): array
    {
        $payload = [
            'model' => $prompt->getModel()->getName(),
        ];

        $easyInputMessage = new EasyInputMessage();

        foreach ($prompt->getInputs() as $input) {
            if ($input instanceof InputText) {
                $content = ResponseInput::asText(
                    text: $input->getText(),
                );
            } else {
                $content = ResponseInput::asFile(
                    $input->getId(), $input->getType(),
                );
            }

            $easyInputMessage->addContent($content);
        }

        $payload['input'] = [$easyInputMessage];

        if (null !== $instructions = $prompt->getInstructions()) {
            $payload['instructions'] = $instructions->getText();
        }

        if ($schema = $prompt->getSchema()) {
            $payload['text'] = [
                'format' => [
                    'type' => 'json_schema',
                    'name' => $schema->getName(),
                    'strict' => $schema->isStrict(),
                    'schema' => $schema->getSchema(),
                ],
            ];
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
    ): bool
    {
        return $data instanceof Prompt && $data->getModel()->getVendor()->isOpenAI();
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
