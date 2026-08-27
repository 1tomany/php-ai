<?php

namespace OneToMany\AI\Exception;

use function sprintf;
use function trim;

final class EmptyIdException extends InvalidArgumentException
{
    /**
     * @return non-empty-string
     */
    public static function validate(?string $id, string $parameter): string
    {
        if ('' === $id = trim((string) $id)) {
            throw new self(sprintf('The %s ID cannot be empty.', $parameter));
        }

        return $id;
    }
}
