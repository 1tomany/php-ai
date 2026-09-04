<?php

namespace OneToMany\AI\Resource\Shared;

use function count;
use function is_array;
use function is_scalar;
use function is_string;
use function trim;

final class Metadata implements \JsonSerializable
{
    /**
     * @var non-negative-int
     */
    private int $count = 0;

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

                if ($value instanceof \DateTimeInterface) {
                    $value = $value->getTimestamp();
                }

                if ($value instanceof \Stringable) {
                    $value = trim((string) $value);
                }

                if ('' !== $key && is_scalar($value)) {
                    $this->metadata[$key] = $value;
                }
            }
        }

        $this->count = count($this->metadata);
    }

    /**
     * @return non-negative-int
     */
    public function count(): int
    {
        return $this->count;
    }

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    /**
     * @return array<non-empty-string, scalar>
     */
    public function toArray(): array
    {
        return $this->metadata;
    }

    /**
     * @see \JsonSerializable
     *
     * @return array<non-empty-string, scalar>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
