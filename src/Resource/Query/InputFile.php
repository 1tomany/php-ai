<?php

namespace OneToMany\AI\Resource\Query;

use OneToMany\AI\Exception\DomainException;

use function trim;

final readonly class InputFile
{
    /**
     * @var non-empty-string
     */
    public string $id;

    /**
     * @var non-empty-lowercase-string
     */
    public string $type;

    /**
     * @throws DomainException when the file ID is empty
     * @throws DomainException when the type is empty
     */
    public function __construct(
        ?string $id,
        ?string $type,
    ) {
        if ('' === $id = trim((string) $id)) {
            throw new DomainException('The file ID cannot be empty.');
        }

        $this->id = $id;

        if ('' === $type = trim((string) $type)) {
            throw new DomainException('The type cannot be empty.');
        }

        $this->type = strtolower($type);
    }

    /**
     * @return non-empty-string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
