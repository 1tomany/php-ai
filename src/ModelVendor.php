<?php

namespace OneToMany\AI;

use OneToMany\AI\Exception\DomainException;

use function explode;
use function sprintf;
use function strtolower;
use function trim;

enum ModelVendor: string
{
    case Gemini = 'gemini';
    case OpenAI = 'openai';

    /**
     * @throws DomainException when the vendor is not valid
     */
    public static function create(string|self $vendor): self
    {
        if (!$vendor instanceof self) {
            if ('' !== $vendor = trim($vendor)) {
                $vendor = strtolower($vendor);
            }

            try {
                return self::from($vendor);
            } catch (\ValueError $e) {
                throw new DomainException(sprintf('The vendor "%s" is not valid.', $vendor), previous: $e);
            }
        }

        return $vendor;
    }

    /**
     * @throws DomainException when the model format is invalid
     */
    public static function fromModel(string $model): self
    {
        $bits = explode(':', trim($model), 2);

        if (!isset($bits[1])) {
            throw new DomainException('The model must use the "vendor:model" format.');
        }

        return self::create($bits[0]);
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @phpstan-assert-if-true self::Gemini $this
     */
    public function isGemini(): bool
    {
        return self::Gemini === $this;
    }

    /**
     * @phpstan-assert-if-true self::OpenAI $this
     */
    public function isOpenAI(): bool
    {
        return self::OpenAI === $this;
    }
}
