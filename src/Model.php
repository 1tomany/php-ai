<?php

namespace OneToMany\AI;

use OneToMany\AI\Exception\DomainException;

use function explode;
use function trim;
use function vsprintf;

final class Model implements \Stringable
{
    /**
     * @var ?non-empty-string
     */
    private ?string $id = null;

    public readonly Vendor $vendor;

    /**
     * @var non-empty-string
     */
    public readonly string $name;

    /**
     * @throws DomainException when the model name is empty
     */
    public function __construct(
        string|Vendor $vendor,
        ?string $name,
    ) {
        if (!$vendor instanceof Vendor) {
            $vendor = Vendor::create($vendor);
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
        return $this->getId();
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

        return new self(Vendor::fromModel($model), $bits[1]);
    }

    public static function gemini(string $name): self
    {
        return new self(Vendor::Gemini, $name);
    }

    public static function openai(string $name): self
    {
        return new self(Vendor::OpenAI, $name);
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        if (null === $this->id) {
            $this->id = vsprintf('%s:%s', [
                $this->vendor->value, $this->name,
            ]);
        }

        return $this->id;
    }

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
