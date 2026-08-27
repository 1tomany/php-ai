<?php

namespace OneToMany\AI\Resource\Shared;

use function is_array;
use function is_scalar;
use function is_string;
use function trim;

final readonly class Metadata
{
    /**
     * @var array<non-empty-string, scalar>
     */
    public array $metadata;

    /**
     * @param ?array<mixed> $metadata
     */
    public function __construct(
        ?array $metadata = null,
    ) {
        $meta = [];

        if (is_array($metadata)) {
            foreach ($metadata as $key => $value) {
                if (!is_string($key)) {
                    continue;
                }

                $key = trim($key);

                if ('' === $key) {
                    continue;
                }

                if (is_scalar($value)) {
                    $meta[$key] = $value;
                }
            }
        }

        $this->metadata = $meta;
    }

    public function isEmpty(): bool
    {
        return [] === $this->metadata;
    }
}
