<?php

namespace OneToMany\AI\Bridge\Meta\Resource\Response;

use OneToMany\AI\Exception\DomainException;

use function trim;

/**
 * @extends InputContent<'input_image'>
 */
final readonly class InputImageContent extends InputContent
{
    /**
     * @var non-empty-string
     */
    public string $fileId;

    /**
     * @see OneToMany\AI\Bridge\Meta\Resource\Response\InputContent
     *
     * @throws DomainException when the file ID is empty
     */
    public function __construct(?string $fileId)
    {
        parent::__construct('input_image');

        if ('' === $fileId = trim((string) $fileId)) {
            throw new DomainException('The file ID cannot be empty.');
        }

        $this->fileId = $fileId;
    }

    /**
     * @see \JsonSerializable
     *
     * @return array{
     *   type: 'input_image',
     *   file_id: non-empty-string,
     * }
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'file_id' => $this->fileId,
        ];
    }
}
