<?php

namespace OneToMany\AI;

use OneToMany\AI\Exception\DomainException;

use function explode;
use function trim;

final readonly class Model implements \Stringable
{
    private ModelVendor $vendor;

    /**
     * @var non-empty-string
     */
    private string $name;

    /**
     * @throws DomainException when the model name is empty
     */
    public function __construct(
        string|ModelVendor $vendor,
        ?string $name,
    ) {
        if (!$vendor instanceof ModelVendor) {
            $vendor = ModelVendor::create($vendor);
        }

        $this->vendor = $vendor;

        if ('' === $name = trim((string) $name)) {
            throw new DomainException('The model name cannot be empty.');
        }

        $this->name = $name;
    }

    /**
     * @see \Stringable
     *
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    public static function create(string|self $model): self
    {
        if ($model instanceof self) {
            return $model;
        }

        $bits = explode(':', $model, 2);

        if (!isset($bits[1])) {
            $bits[1] = null;
        }

        return new self(ModelVendor::fromModel($model), $bits[1]);
    }

    public static function gemini(string $name): self
    {
        return new self(ModelVendor::Gemini, $name);
    }

    public static function openai(string $name): self
    {
        return new self(ModelVendor::OpenAI, $name);
    }

    public function getVendor(): ModelVendor
    {
        return $this->vendor;
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return sprintf('%s:%s', $this->vendor->value, $this->name);
    }
}
