<?php

namespace OneToMany\AI\Exception;

use OneToMany\AI\Contract\Exception\ExceptionInterface;

class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * @return non-empty-string
     */
    public static function validateId(?string $id, string $parameter): string
    {
        if ('' === $id = trim((string) $id)) {
            throw new self(sprintf('The %s ID cannot be empty.', $parameter));
        }

        return $id;
    }
}
