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
    public string $mimeType;

    /**
     * @throws DomainException when the file ID is empty
     * @throws DomainException when the MIME type is empty
     */
    public function __construct(
        ?string $id,
        ?string $mimeType,
    ) {
        if ('' === $id = trim((string) $id)) {
            throw new DomainException('The file ID cannot be empty.');
        }

        $this->id = $id;

        if ('' === $mimeType = trim((string) $mimeType)) {
            throw new DomainException('The MIME type cannot be empty.');
        }

        $this->mimeType = strtolower($mimeType);
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
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}
