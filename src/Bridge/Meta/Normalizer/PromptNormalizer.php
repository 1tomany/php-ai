<?php

namespace OneToMany\AI\Bridge\Meta\Normalizer;

use OneToMany\AI\Bridge\Meta\Resource\Response\CreateResponse;
use OneToMany\AI\Bridge\Meta\Resource\Response\EasyInputMessage;
use OneToMany\AI\Bridge\Meta\Resource\Response\InputContent;
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
        $message = new EasyInputMessage();

        foreach ($prompt->getInputs() as $input) {
            if ($input instanceof InputText) {
                $content = InputContent::asText(
                    text: $input->getText(),
                );
            } else {
                $content = InputContent::asFile(
                    $input->getId(), $input->getType(),
                );
            }

            $message->addContent($content);
        }

        $schema = $prompt->getSchema();
        $response = new CreateResponse(
            model: $prompt->getModelId(),
            input: [$message],
            instructions: $prompt->getInstructions()?->getText(),
            text: null !== $schema ? [
                'format' => [
                    'type' => 'json_schema',
                    'name' => $schema->getName(),
                    'strict' => $schema->isStrict(),
                    'schema' => $schema->getSchema(),
                ],
            ] : null,
        );

        return array_replace($prompt->getOptions(), $response->jsonSerialize());
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
        return $data instanceof Prompt && $data->getModel()->getVendor()->isMeta();
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
