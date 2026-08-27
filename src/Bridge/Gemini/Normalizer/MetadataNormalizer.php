<?php

namespace OneToMany\AI\Bridge\Gemini\Normalizer;

use OneToMany\AI\Resource\Shared\Metadata;
use OneToMany\AI\Vendor;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function is_bool;
use function is_float;
use function is_int;

final readonly class MetadataNormalizer implements NormalizerInterface
{
    /**
     * @see Symfony\Component\Serializer\Normalizer\NormalizerInterface
     *
     * @param Metadata $data
     *
     * @return ?list<
     *   array{
     *     key: non-empty-string,
     *     numericValue?: int|float,
     *     stringValue?: string,
     *   }
     * >
     */
    #[\Override]
    public function normalize(
        mixed $data,
        ?string $format = null,
        array $context = [],
    ): ?array {
        $metadata = [];

        foreach ($data->metadata as $key => $value) {
            if (is_bool($value)) {
                $value = (int) $value;
            }

            if (
                is_float($value)
                || is_int($value)
            ) {
                $metadata[] = [
                    'key' => $key,
                    'numericValue' => $value,
                ];
            } else {
                $metadata[] = [
                    'key' => $key,
                    'stringValue' => $value,
                ];
            }
        }

        return [] !== $metadata ? $metadata : null;
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
        return $data instanceof Metadata && Vendor::Gemini->getName() === $format;
        // if ($data instanceof Metadata) {
        //     $vendor = $context['vendor'] ?? null;

        //     if ($vendor instanceof Vendor) {
        //         return $vendor->isGemini();
        //     }
        // }

        // return false;
    }

    /**
     * @see Symfony\Component\Serializer\Normalizer\NormalizerInterface
     */
    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return [
            Metadata::class => false,
        ];
    }
}
