<?php

namespace OneToMany\AI\Resource\Shared;

use function is_array;
use function is_scalar;
use function is_string;
use function trim;

final class Metadata implements \JsonSerializable
{
    /**
     * @var array<non-empty-string, scalar>
     */
    private array $metadata = [];

    /**
     * @param ?array<mixed> $metadata
     */
    public function __construct(
        ?array $metadata = null,
    ) {
        if (is_array($metadata)) {
            foreach ($metadata as $key => $value) {
                if (!is_string($key)) {
                    continue;
                }

                $key = trim($key);

                if ('' !== $key && is_scalar($value)) {
                    $this->metadata[$key] = $value;
                }
            }
        }
    }

    public function isEmpty(): bool
    {
        return [] === $this->metadata;
    }

    /**
     * @see \JsonSerializable
     *
     * @return array<non-empty-string, scalar>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->metadata;
    }
}
