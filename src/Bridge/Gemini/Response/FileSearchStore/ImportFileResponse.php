<?php

namespace OneToMany\AI\Bridge\Gemini\Response\FileSearchStore;

use function trim;

final readonly class ImportFileResponse
{
    /**
     * @var ?non-empty-string
     */
    public ?string $parent;

    /**
     * @var ?non-empty-string
     */
    public ?string $documentName;

    public function __construct(
        ?string $parent = null,
        ?string $documentName = null,
    ) {
        if (null !== $parent) {
            $parent = trim($parent);
        }

        $this->parent = '' !== $parent ? $parent : null;

        if (null !== $documentName) {
            $documentName = trim($documentName);
        }

        $this->documentName = '' !== $documentName ? $documentName : null;
    }
}
